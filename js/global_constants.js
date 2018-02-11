const STATUS_REQUEST = "update_request";
const STATUS_UPDATE = "status_update";
const CONTACT_MESSAGE = "contact_message";
const NETWORK_UNREACHABLE = "network_unreachable";
const CHECK_IN_USER = "check_in_user";
const CHECK_IN_COMPLETE = "check_is_complete";
const LOAD_NOTIFICATION = "load_notification";
const ADD_CONTACT_NOTIFICATION = "add_contact_notification"
const STATUS_ONLINE = 1;
const STATUS_OFFLINE = 0;
const STATUS_IDLE = 2;
const STATUS_BUSY = 3;

//requests/notifications sent to another 
const ADD_CONTACT_REQUEST = "add_contact_request";
const JOIN_GROUP_REQUEST = "join_group_request";
const REQUEST_NOTIFICATION = "request_notification";

//Service request
const SEARCH_USERS = "search_users";
const REQUEST_CONTACTS_LIST = "request_contacts_list";
const REQUEST_MESSAGES = "request_messages";


/****************************************************************
This script contains function for processing Notifications
*/
const ONLINE_IMG_URL="http://192.168.1.158/images/online.jpg";
const BUSY_IMG_URL="http://192.168.1.158/images/busy.jpg";
const OFFLINE_IMG_URL="http://192.168.1.158/images/offline.png"; 
const IDLE_IMG_URL = "http://192.168.1.158/images/idle.jpg"; 

/****************************************************************
*
*/
const ACCOUNT_SERVICE_URL = "http://192.168.1.158/php/service/account_service.php";
