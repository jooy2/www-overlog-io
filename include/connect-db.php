<?php
    class ConnectDB {
        private static $connect_db;
        private $connection = null;

        public static function getInstance() {
            if (!isset(ConnectDB::$connect_db))
                ConnectDB::$connect_db = new ConnectDB('');
            return ConnectDB::$connect_db;
        }

        function __construct() {
        }

        function __destruct() {
        }

        function connect() {
            try {
                $config = parse_ini_file('.connection_conf.ini');
                $dbhost = $config['servername'];
                $dbus = $config['username'];
                $dbpw = $config['password'];
                $dbname = $config['dbname'];

                $this->connection = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbus, $dbpw);
            } catch (PDOException $e) {
                echo "<div style='padding:15px;margin:20px;background:white;border:1px solid #6f6f6f;color:#555'>
                        <h3>연결 문제 발생</h3><p>데이터베이스에 접속하지 못하였습니다.</p></div>";
            }
        }

        function db_terminate() {
            $connection = null;
        }

        /** =====================================================================
         * User authentications
         * ====================================================================== */
        function auth_check($account, $password) {
            $this->connect();
            $password = hash("sha256", $password);
            
            try {
                $query = $this->connection->prepare("SELECT u_account FROM login_users
                                            WHERE u_passwd=? AND u_account=? LIMIT 1;");
                $query->execute([$password, $account]);
                $query->setFetchMode(PDO::FETCH_ASSOC);

                $row = $query->fetch();

                return !empty($row['u_account']);
            } catch (PDOException $e) {
                return false;
            }
            return false;
        }

        function get_user_data($account) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT u_id, u_account, u_name, u_is_admin,
                                                u_email, u_reg_date, u_mod_date
                                                FROM login_users
                                                WHERE u_account=? LIMIT 1;");
                $query->execute([$account]);
                $query->setFetchMode(PDO::FETCH_ASSOC);

                return $query->fetch();
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function user_exist_check($user_id) {
            $this->connect();

            try {
                $query = $this->connection->prepare("SELECT u_account FROM login_users
                                                WHERE u_account=? LIMIT 1;");
                
                $query->execute([$user_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);

                $row = $query->fetch();
                
                return !empty($row['u_account']);
            } catch (PDOException $e) {
                return false;
            }
        }
        
        function register_user($account, $password, $name, $email) {
            $this->connect();
            $password = hash("sha256", $password);
            
            try {
                $query = $this->connection->prepare("INSERT INTO login_users
                                                        (u_account, u_passwd, u_email, u_name, u_reg_date)
                                                        VALUES (?, ?, ?, ?, ?);");
                $query->execute([$account, $password, $email, $name, get_datetime()]);
                return true;
            } catch (PDOException $e) {
                return false;
            }
            return false;
        }

        /** =====================================================================
         * Dashboard
         * ====================================================================== */
        function get_dashboard_data() {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT m_id, m_is_active, m_name, m_desc, m_os_type,
                                                            m_dashboard_type, m_icon, m_host_ip, m_host_domain
                                                        FROM user_monitor WHERE u_id=? AND m_is_obsolete=0 LIMIT 100;");
                $query->execute([get_user_no()]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $rowCount = $query->rowCount();
                
                $str = "";

                while ($row = $query->fetch()) {
                    $mID = $row['m_id'];

                    if ($row['m_is_active'] == "1") {
                        $stat_color = "green";
                        $stat_message = "운영중";
                    }
                    else {
                        $stat_color = "red";
                        $stat_message = "중지됨";
                    }

                    $str .= "<div class='ui card column'>
                        <div class='content'>
                            <img class='right floated mini ui' src='".get_ico($row['m_icon'], 48)."'>
                            <div class='header'>
                                <i class='circle $stat_color icon popup' data-content='$stat_message'></i>
                                ".$row['m_name']."
                            </div>
                            <div class='meta'>
                            ".$row['m_host_domain']." (".$row['m_host_ip'].")
                            </div>
                            <div class='description'>
                                <p>".$row['m_desc']."</p>
                                <p><i class='laptop icon icon-pad-right popup' data-content='운영체제'></i>".$this->get_operation_type_name($row['m_os_type'])."</p>
                            </div>
                        </div>
                        <div class='extra content'>
                            <p><i class='eye icon icon-pad-right'></i>".$this->get_monitor_type_desc($row['m_dashboard_type'])."</p>
                            ".$this->get_monitor_chart($row['m_dashboard_type'], $row['m_id'])."
                            <div class='ui two buttons'>
                                <div data-monitoring-id='".$row['m_id']."' class='ui blue button btn-monitoring-details'>자세히</div>
                                <div data-monitoring-id='".$row['m_id']."' class='ui button btn-monitoring-settings'>설정</div>
                            </div>
                        </div>
                    </div>";
                }

                $str .= "<div id='list-count' class='hidden'>".number_format($rowCount)."</div>";

                return $str;
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function set_dashboard_data($user_no, $name, $desc, $hostname,
                                        $ip, $os, $type, $icon, $token) {
            $this->connect();

            try {
                $query = $this->connection->prepare("INSERT INTO user_monitor(u_id, m_name, m_desc, m_host_domain,
                                                                m_host_ip, m_os_type, m_data_type, m_icon, m_token, m_reg_date)
                                                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
                $query->execute([$user_no, $name, $desc, $hostname, $ip, $os, $type, $icon, $token, get_datetime()]);
                return true;
            } catch (PDOException $e) {
                return false;
            }
            return false;
        }

        /** =====================================================================
         * User configurations
         * ====================================================================== */
        function reset_password($rep_password, $user_no) {
            $this->connect();

            $password = hash("sha256", $rep_password);
            
            try {
                $query = $this->connection->prepare("UPDATE login_users SET u_passwd=? WHERE u_id=?;");
                $query->execute([$password, $user_no]);
                
                return true;
            } catch (PDOException $e) {
                return false;
            }
            return false;
        }

        /** =====================================================================
         * Device configurations
         * ====================================================================== */
        function set_server_status($monitor_id, $enabled) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("UPDATE user_monitor SET m_is_active=? WHERE m_id=?;");
                $query->execute([$enabled, $monitor_id]);
                
                return true;
            } catch (PDOException $e) {
                return false;
            }
            return false;
        }

        function del_monitor_by_id($monitor_id) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("UPDATE user_monitor SET m_is_obsolete=1 WHERE m_id=?;");
                $query->execute([$monitor_id]);
                
                return true;
            } catch (PDOException $e) {
                return false;
            }
            return false;
        }

        function get_operation_type_name($type) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT d_name FROM data_log_os WHERE d_id=? LIMIT 1;");
                $query->execute([$type]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $row = $query->fetch();
                
                if (!empty($row['d_name']))
                    return $row['d_name'];
                else
                    return null;
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function get_monitor_type_name($type) {
            switch($type) {
                case 0:
                    return "disk";
                case 1:
                    return "cpu";
                case 2:
                    return "mem";
                default:
                    return "disk";
            }
        }

        function get_monitor_type_desc($type) {
            switch($type) {
                case 0:
                    return "디스크 사용";
                case 1:
                    return "CPU 사용";
                case 2:
                    return "메모리 사용";
                default:
                    return "디스크 사용";
            }
        }

        function get_monitor_chart($dashboard_type, $monitor_id) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT *
                                                        FROM log_monitor WHERE m_id=? ORDER BY l_timestamp DESC LIMIT 1;");
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $row = $query->fetch();
                
                switch ($dashboard_type) {
                    case 0:
                        return $this->get_chart_html($monitor_id, "disk", $row['l_disk_use'], $row['l_disk_total']);
                    case 1:
                        return $this->get_chart_html($monitor_id, "cpu", $row['l_cpu_use'], $row['l_cpu_sys']);
                    case 2:
                        return $this->get_chart_html($monitor_id, "mem", $row['l_mem_use'], $row['l_mem_total']);
                    default:
                        return "";
                }
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function get_monitor_info_by_token($token) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT m_id, m_data_type, m_is_active, m_is_obsolete FROM user_monitor WHERE m_token=? LIMIT 1;");
                $query->execute([$token]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $row = $query->fetch();
                
                if (isset($row['m_data_type']) && isset($row['m_id'])) {
                    if ($row['m_is_active'] == "0" || $row['m_is_obsolete'] == "1")
                        return "denied";
                    else
                        return $row['m_id'] . "//" . $row['m_data_type'];
                } else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function get_monitor_info_by_id($monitor_id) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT * FROM user_monitor WHERE m_id=? LIMIT 1;");
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                return $query->fetch();
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function get_chart_html($monitor_id, $monitor_type, $first_value, $second_value) {
            return "<div class='chart-area ui centered grid mar-1y' id='chart-$monitor_id'
                        data-monitor-id='$monitor_id' data-monitor-type='$monitor_type' data-first-val='$first_value'
                        data-second-val='$second_value'></div>";
        }

        function get_select_card($type, $table, $avail_type) {
            $this->connect();
            
            try {
                if ($avail_type != null)
                    $whereStr = "WHERE d_avail='$avail_type'";
                else
                    $whereStr = "";
                $query = $this->connection->prepare("SELECT * FROM $table $whereStr LIMIT 100;");
                $query->execute([]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $str = "";
                
                while ($row = $query->fetch()) {
                    $str .= "
                    <div class='card'>
                        <div class='content'>
                            <div class='header'>
                                <p><i class='".$row['d_icon']." icon'></i>
                                ".$row['d_name']."</p>
                            </div>
                            <div class='description'>
                                <p>".$row['d_desc']."</p>";
                                
                    if ($type == "type")
                        $str .= "<p><i class='user icon'></i>".$row['d_author']."</p>";

                    $str .= "
                            </div>
                        </div>
                        <div class='ui bottom attached button data-$type-select'
                             data-$type-id='".$row['d_id']."' data-name='".$row['d_name']."',
                             data-icon='".$row['d_icon']."'>
                             ";
                    
                    if ($type == "type")
                        $str .= "<pre class='data-type-sample hidden'>".$row['d_sample']."</pre>";
                    
                    $str .="<i class='upload icon'></i>선택
                        </div>
                    </div>";
                }
                return $str;
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function get_log_monitor_by_id($monitor_id) {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT * FROM log_monitor WHERE m_id=?
                                                        ORDER BY l_timestamp DESC LIMIT 100;");
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $str = "";

                while ($row = $query->fetch()) {
                    $str .= "<tr>";
                    $str .= "<td>".$row['l_no']."</td>";
                    $str .= "<td>".$row['l_cpu_use']."</td><td>".$row['l_cpu_sys']."</td>";
                    $str .= "<td>".$row['l_mem_use']."</td><td>".($row['l_mem_total'] - $row['l_mem_use'])."</td>";
                    $str .= "<td>".$row['l_disk_use']."</td><td>".($row['l_disk_total'] - $row['l_disk_use'])."</td>";
                    $str .= "<td>".$row['l_network_rx_byte']."</td><td>".$row['l_network_tx_byte']."</td>";
                    $str .= "<td>".$row['l_network_rx_packet']."</td><td>".$row['l_network_tx_packet']."</td>";
                    $str .= "<td>".$row['l_timestamp']."</td>";
                    $str .= "</tr>";
                }

                return $str;
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        /** =====================================================================
         * Get logs
         * ===================================================================== */
        function get_all_log_monitor_json($monitor_id) {
            $this->connect();
            
            try {
                if ($monitor_id == -1) {
                    $query = $this->connection->prepare("SELECT * FROM log_monitor
                                                        ORDER BY l_timestamp DESC LIMIT 100;");
                } else {
                    $query = $this->connection->prepare("SELECT * FROM log_monitor WHERE m_id=?
                                                        ORDER BY l_timestamp DESC LIMIT 100;");
                }
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $str = array();
                $strTemp = array();

                while ($row = $query->fetch()) {
                    $strTemp = array("id"=>$row['l_id'], "number"=>$row['l_no'], "os"=>$row['l_os_name'], "hostname"=>$row['l_host_name'],
                                    "cpu-use"=>$row['l_cpu_use'], "cpu-sys"=>$row['l_cpu_sys'],
                                    "disk-use"=>$row['l_disk_use'], "disk-total"=>$row['l_disk_total'],
                                    "mem-use"=>$row['l_mem_use'], "mem-total"=>$row['l_mem_total'],
                                    "cpu-top"=>$row['l_cpu_top'], "mem-top"=>$row['l_mem_top'],
                                    "network-rx-byte"=>$row['l_network_rx_byte'], "network-tx-byte"=>$row['l_network_tx_byte'],
                                    "network-rx-packet"=>$row['l_network_rx_packet'], "network-tx-packet"=>$row['l_network_tx_packet'],
                                    "timestamp"=>$row['l_timestamp']);
                    array_push($str, $strTemp);
                }

                return $str;
            } catch (PDOException $e) {
                return null;
            }
            return null;
        }

        function get_last_log_by_monitor_id($monitor_id) {
            try {
                $query = $this->connection->prepare("SELECT * FROM log_monitor
                    WHERE m_id=? ORDER BY l_no DESC LIMIT 1;");
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);

                return $query->fetch();
            } catch (PDOException $e) {
                return -1;
            }
            return -1;
        }

        function get_last_log_no_by_monitor_id($monitor_id) {
            try {
                $query = $this->connection->prepare("SELECT l_no FROM log_monitor
                    WHERE m_id=? ORDER BY l_no DESC LIMIT 1;");
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $row = $query->fetch();
                return $row['l_no'];
            } catch (PDOException $e) {
                return -1;
            }
            return -1;
        }

        function get_last_log_graph($monitor_id) {
            try {
                $query = $this->connection->prepare("SELECT * FROM log_monitor
                    WHERE m_id=? ORDER BY l_no DESC LIMIT 1;");
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $str = "";
                $row = $query->fetch();
                $type_name = "";

                $value1Arr = array($row['l_disk_use'], $row['l_cpu_use'], $row['l_mem_use']);
                $value2Arr = array($row['l_disk_total'], $row['l_cpu_sys'], $row['l_mem_total']);

                for ($i=0; $i<3; $i++) {
                    $str .= "<div class='ui card column'>
                    <div class='extra content'>
                        <p><i class='eye icon icon-pad-right'></i>".$this->get_monitor_type_desc($i)."</p>
                        ".$this->get_chart_html($i, $this->get_monitor_type_name($i), $value1Arr[$i], $value2Arr[$i])."
                    </div>
                </div>";
                }

                return $str;
            } catch (PDOException $e) {
                return "";
            }
            return "";
        }

        function get_last_top_process($type, $monitor_id) {
            try {
                $query = $this->connection->prepare("SELECT l_".$type."_top FROM log_monitor
                    WHERE m_id=? ORDER BY l_no DESC LIMIT 1;");
                $query->execute([$monitor_id]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $result = "<div class='ui ordered horizontal list'>";

                $row = $query->fetch();

                $top_str = explode(";;", $row['l_'.$type.'_top']);

                foreach ($top_str as $str) {
                    $result .= "<div class='item'>
                                    <img class='ui bordered avatar image' src='".get_process($str)."'>
                                    <div class='content'>
                                        <div class='header'>$str</div>
                                        50 Points
                                    </div>
                                </div>";
                }

                $result .= "</div>";

                return $result;
            } catch (PDOException $e) {
                return "";
            }
            return "";
        }

        /** =====================================================================
         * Set logs
         * ===================================================================== */
        function send_log_monitor($data_id, $data) {
            $this->connect();

            if ($data == null)
                return false;
            
            try {
                $current_no = ($this->get_last_log_no_by_monitor_id($data_id)) + 1;
                $query = $this->connection->prepare("INSERT INTO log_monitor(
                    m_id, l_no, l_os_name, l_host_name,
                    l_cpu_use, l_cpu_sys, l_cpu_top, l_mem_use, l_mem_total, l_mem_top,
                    l_network_rx_byte, l_network_tx_byte, l_network_rx_packet, l_network_tx_packet,
                    l_disk_use, l_disk_total, l_note) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
                $query->execute([$data_id, $current_no, $data[1], $data[2],
                                $data[3], $data[4], $data[5], $data[6], $data[7], $data[8],
                                $data[9], $data[10], $data[11], $data[12],
                                $data[13], $data[14], $data[15]]);
                return true;
            } catch (PDOException $e) {
                return false;
            }
            return false;
        }
    }