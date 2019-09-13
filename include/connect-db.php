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
                        <h3>연결 문제 발생</h3><p>데이터베이스에 접속하지 못하였습니다.</p><p>Message: ". $e ."</p></div>";
            }
        }

        function db_terminate() {
            $connection = null;
        }

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

        function get_dashboard_data() {
            $this->connect();
            
            try {
                $query = $this->connection->prepare("SELECT m_id, m_is_active, m_name, m_desc, m_os_type,
                                                            m_dashboard_type, m_icon, m_host_ip, m_host_domain
                                                        FROM user_monitor WHERE u_id=? LIMIT 100;");
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
                            <p><i class='laptop icon icon-pad popup' data-content='운영체제'></i>".$this->get_operation_type_name($row['m_os_type'])."</p>
                        </div>
                    </div>
                    <div class='extra content'>
                        <p><i class='eye icon icon-pad'></i>".$this->get_monitor_type_name($row['m_dashboard_type'])."</p>
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
                    return "디스크 사용";
                case 1:
                    return "CPU 사용";
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
                $query = $this->connection->prepare("SELECT m_id, m_data_type FROM user_monitor WHERE m_token=? LIMIT 1;");
                $query->execute([$token]);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                
                $row = $query->fetch();
                
                if (isset($row['m_data_type']) && isset($row['m_id'])) {
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

        function send_log_monitor($data_id, $data) {
            $this->connect();

            if ($data == null)
                return false;
            
            try {
                $query = $this->connection->prepare("INSERT INTO log_monitor(
                    m_id, l_os_name, l_host_name,
                    l_cpu_use, l_cpu_sys, l_cpu_top, l_mem_use, l_mem_total, l_mem_top,
                    l_network_rx_byte, l_network_tx_byte, l_network_rx_packet, l_network_tx_packet,
                    l_disk_use, l_disk_total, l_note) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
                $query->execute([$data_id, $data[1], $data[2],
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