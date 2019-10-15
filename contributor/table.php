<?php
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
require_once( __DIR__ . '/../const.php' );


class Inquiry_Contributor_List_Table extends WP_List_Table
{
    public function prepare_items() {
        global $LIST_LIMIT;
        $this->set_pagination_args( array(
            'total_items' => $this->get_total(),
            'per_page'    => $LIST_LIMIT
        ));

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->get_parsed_data();
    }

    private function get_parsed_data() {
        $result = [];
        foreach ($this->get_data() as $res){
            $converted_gender = CONVERT_GENDER_CODE($res->gender);
            $result[] = array(
                "id" => $res->id,
                "inquirer_info" => "Age: $res->age<br>Gender: $converted_gender<br>Country: $res->country",
                "message" => $res->message,
                "status" => CONVERT_STATUS_CODE($res->status),
                "response" => $res->response ? $res->response : "No Response",
                "time" => CONVERT_TIME($res->time),
                "assignee_name" => $res->assignee_name ? $res->assignee_name : "Not Assigned",

            );
        }
        return $result;
    }

    function column_inquirer_info($item) {
        $actions = array();
        $inquiry_id = $item["id"];
        $current_user_id = get_current_user_id();

        $inquirer_info = $item['inquirer_info'];
        $message = $item["message"];
        $response = $item["response"];

        if ($item["assignee_name"] != "Not Assigned"){
            if ($item["assignee_name"] == wp_get_current_user()->user_login){
                $actions["delete"] = "<a onclick='unassign_inquiry($inquiry_id, $current_user_id)' href='#'>Unassign</a>";
                $actions["edit"] = "<a 
                    onclick='open_inquiry_modal($inquiry_id, `$inquirer_info`, `$message`, `$response`)' 
                    href='#'>Edit Response</a>";
            } else {
                $actions["edit"] = "<a onclick='open_comment_modal($inquiry_id, `$inquirer_info`, `$message`, `$response`)' 
                    href='#'>Comment</a>";
            }
        } else {
            $inquiry_id = $item["id"];
            $actions["edit"] =
                "<a onclick='assign_inquiry($inquiry_id, $current_user_id)' href='#'>Take This Inquiry</a>";
        }

        return sprintf('%1$s %2$s', $item['inquirer_info'], $this->row_actions($actions) );
    }

    private function get_data() {
        global $wpdb, $INQUIRY_TABLE_NAME, $USER_TABLE_NAME, $LIST_LIMIT;
        // Orders
        $order_by = 'time';
        $order = 'desc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $order_by = $_GET['orderby'];
            if (!$this->get_columns()[$order_by]){
                echo "You SQL injection activity is recorded!";
                $order_by = 'time';
            }
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
            if (!($order == "desc" or $order == "asc")){
                echo "You SQL injection activity is recorded!";
                $order = "desc";
            }
        }

        // Paging
        $offset = ((int) $this->get_pagenum() - 1) * $LIST_LIMIT;

        // Where
        $where = "";
        if($_GET['status'] != null) {
            $status = (int) $_GET['status'];
            $where = $wpdb->prepare("WHERE i.status = %d", $status);
        }

        return $wpdb->get_results($wpdb->prepare(
            "
                SELECT 
                    i.id,
                    i.age, i.gender, i.country, i.message, i.status, i.response, i.time,
                    assignee.user_login as assignee_name,
                    assigner.user_login as assigner_name
                FROM $INQUIRY_TABLE_NAME AS i
                LEFT JOIN $USER_TABLE_NAME AS assignee ON i.assignee_id = assignee.id
                LEFT JOIN $USER_TABLE_NAME AS assigner ON i.assigner_id = assigner.id
                $where
                ORDER BY i.$order_by $order
                LIMIT %d, %d 
            ",
            $offset, $LIST_LIMIT
        ));
    }

    private function get_total() {
        global $wpdb, $INQUIRY_TABLE_NAME;
        $result = $wpdb->get_results("SELECT count(*) AS c FROM $INQUIRY_TABLE_NAME");
        return $result[0]->c;
    }

    public function get_columns() {
        $columns = array(
            "inquirer_info" => "Inquirer Info",
            "message" => "Message",
            "status" => "Status",
            "response" => "Response",
            "time" => "Time",
            "assignee_name" => "Assignee",
        );
        return $columns;
    }

    public function get_hidden_columns() {
        return array();
    }

    public function get_sortable_columns() {
        return array(
            'time' => array('time', false),
            'status' => array('status', false),
        );
    }

    public function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'id':
            case 'inquirer_info':
            case 'message':
            case 'status':
            case 'response':
            case 'time':
            case 'assignee_name':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }



}
