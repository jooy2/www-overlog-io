![overlog-logo-horizontal](https://user-images.githubusercontent.com/48266008/125254904-e0d8ab80-e335-11eb-95e1-768909eb45ff.png)

**Overlog** | 2019 대림대학교 캡스톤디자인 프로젝트

다양한 운영체제에서 수집된 하드웨어 및 네트워크 정보를 수집하여 모니터링할 수 있는 웹 사이트입니다.
외부 수집 프로그램 또는 수동적인 방법으로 특정 URI에 값을 전달하면 로그가 수집됩니다.

다음으로 이메일, CSV, HTML Table 등의 여러 데이터 형식의 파일들을 하나의 형식으로 통합하여 쉽게 분석하거나, 저장할 수 있습니다.

## 프로젝트 정보
- 작업 기간: 2019.09.03 ~ 2019.09.30

## 주요 기능
- 계정으로 관리되는 시스템
- URI 요청 방식의 다양한 로그 데이터 수집 및 모니터링
- 여러 데이터 형식의 파일을 분석하고 저장

## 요구사항
- Nginx 1.12.2
- PHP 7.3
- MariaDB 10.3.13

일부 페이지의 URI 처리를 위해 nginx 규칙 설정 시 다음과 같은 내용이 들어가야 합니다. 이외의 nginx 설정 방법은 다른 웹문서를 참조합니다.

    location ~ ^/api/collect/(.*)/(.*) {
        return 307 /api/collect/?token=$2&type=$1;
    }

`/include` 디렉토리의 `config.php` 파일을 열어 다음과 같이 수정해줍니다.
- SITE_HOME의 값을 현재 호스트 IP 또는 도메인으로 설정
- SITE_IMG의 값을 이미지 호스팅 주소로 설정

데이터베이스 접근을 위해 `/include` 디렉토리에 `.connection_conf.ini` 파일을 생성합니다.
이후 다음과 같은 규칙으로 파일을 수정합니다.

    [database]
    servername = "데이터베이스 호스트"
    username = "사용자 이름"
    password = "사용자 암호"
    dbname = "데이터베이스 이름"
