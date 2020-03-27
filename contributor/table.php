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

    public function shorten($v){
        $v = strip_tags($v);
        if (strlen($v) < 20){
            return $v;
        } else {
            return substr($v, 0, 20) . "...";
        }
    }

    private function toggle_modal_text($v){
        return "<div onclick='toggle_modal_unchange(`$v`)'>" . $this->shorten($v) . "</div>";
    }

    private function get_parsed_data() {
        $result = [];
        foreach ($this->get_data() as $res){
            $result[] = array(
                "id" => $res->id,
                "email" => $res->email,
                "inquirer_info" => "Age: $res->age<br>Gender: $res->gender<br>Country: $res->country",
                "message" => $this->toggle_modal_text($res->message),
                "message_raw" => $res->message,
                "response" => $this->toggle_modal_text($res->response),
                "response_raw" => $res->reponse,
                "status" => CONVERT_STATUS_CODE($res->status),
                "time" => CONVERT_TIME($res->time),
                "assignee_name" => $res->assignee_name ? $res->assignee_name : "Not Assigned",
                "is_emergency" => $res->is_emergency,
            );
        }
        return $result;
    }

    private function get_comment_count($inquiry_id){
        global $wpdb, $COMMENT_TABLE_NAME;
        $inquiry_info_obj = $wpdb->get_results(
            $wpdb->prepare("
              SELECT 
                COUNT(*) AS comment_count
              FROM $COMMENT_TABLE_NAME AS c
              WHERE c.inquiry_id = %d
            "
                , $inquiry_id
            )
        );
        return $inquiry_info_obj[0]->comment_count;
    }

    function column_id($item) {
        $actions = array();
        $inquiry_id = $item["id"];
        $current_user_id = get_current_user_id();
        $inquirer_info = $item['inquirer_info'];
        $message = $item["message_raw"];
        $response = $item["response_raw"];
        $comment_count = $this->get_comment_count($item["id"]);
        $plural_comment = $comment_count ? "s": "";
        $actions["comment"] = "<a onclick='open_comment_modal($inquiry_id, `$inquirer_info`, `$message`, `$response`)' 
                    href='#'>Comment$plural_comment ($comment_count)</a>";
        if ($item["assignee_name"] != "Not Assigned"){

            if ($item["assignee_name"] == wp_get_current_user()->user_login){
                if ($item["status"] != "Sent"){
                    $actions["delete"] =
                        "<a onclick='unassign_inquiry($inquiry_id, $current_user_id)' href='#'>Unassign</a>";
                    $actions["edit"] = "<a 
                        onclick='open_inquiry_modal($inquiry_id, `$inquirer_info`, `$message`, `$response`)' 
                        href='#'>Edit Response</a>";
                } else {
                    $actions["edit"] = "<a 
                        onclick='open_inquiry_modal_with_confirm($inquiry_id, `$inquirer_info`, `$message`, `$response`)' 
                        href='#'>Edit Response</a>";
                }

            }
        } else {
            $inquiry_id = $item["id"];
            $actions["edit"] =
                "<a onclick='assign_inquiry($inquiry_id, $current_user_id)' href='#'>Take This Inquiry</a>";
        }
        return sprintf('%1$s %2$s', $item['id'], $this->row_actions($actions) );
    }

    function column_message($item) {
        $actions = array();
        $is_emergency = $item['is_emergency'];
        $inquiry_id = $item["id"];
        if ($is_emergency){
            $actions["edit"] =
                "<a onclick='cancel_emergency($inquiry_id)' href='#'>Cancel Emergency</a>";
        } else {
            $actions["edit"] =
                "<a onclick='make_emergency($inquiry_id)' href='#'>Mark as Emergency</a>";
        }
        return sprintf('%1$s %2$s', $item['message'], $this->row_actions($actions) );
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
                    i.email,
                    i.is_emergency,
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
            "id" => "Inquiry ID",
            "email" => "Email",
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
            case 'email':
            case 'assignee_name':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    function single_row( $content ) {
        $cl = $content["is_emergency"] == 1 ? "class='emergency'": "";
        echo "<tr $cl>";
        echo $this->single_row_columns( $content );
        echo "</tr>\n";
    }
}
