<?php

class Ad_Rotate_Email_Addon_Send_Email {

    public function send_email($id,$month) {
        global $wpdb;
        $year  = date( "Y" );

        $from  = mktime( 0, 0, 0, $month, 1, $year );
        $until = mktime( 0, 0, 0, $month + 1, 0, $year );

        $ads = $wpdb->get_results( $wpdb->prepare( "SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `" . $wpdb->prefix . "adrotate_stats` WHERE (`thetime` >= '" . $from . "' AND `thetime` <= '" . $until . "') AND `ad` = %d GROUP BY `thetime` ASC;", $id ), ARRAY_A );

        $title   = $wpdb->get_var( $wpdb->prepare( "SELECT `title` FROM `" . $wpdb->prefix . "adrotate` WHERE `id` = %d;", $id ) );
        $user_id = $wpdb->get_var("SELECT `meta_value` FROM `".$wpdb->prefix."adsmeta` WHERE `ad` = $id AND `meta_key` = 'advertiser';");
        $user = $wpdb->get_results( $wpdb->prepare( "SELECT `user_email`,`display_name` FROM `" . $wpdb->prefix . "users`  WHERE `ID` = %d;", $user_id ), ARRAY_A );

        $useremail = $user[0]['user_email'];
        $topic    = array( "Report for ad '" . $title . "'" );
        $siteurl 	= get_option('siteurl');
        $email   = get_option( 'admin_email' );

        $x=0;
        foreach($ads as $ad) {
            // Prevent gaps in display
            if($ad['impressions'] == 0) $ad['impressions'] = 0;
            if($ad['clicks'] == 0) $ad['clicks'] = 0;

            // Build array
            $adstats[$x]['day']	= date_i18n("M d Y", $ad['thetime']);
            $adstats[$x]['clicks'] = $ad['clicks'];
            $adstats[$x]['impressions'] = $ad['impressions'];
            $x++;
        }

        $x= 0;
        $adstats1 = array();
        foreach ( $ads as $ad ) {
            // Prevent gaps in display
            if ( $ad['impressions'] == 0 ) {
                $ad['impressions'] = 0;
            }
            if ( $ad['clicks'] == 0 ) {
                $ad['clicks'] = 0;
            }
            // Build array
            $adstats1['clicks'] += $ad['clicks'];
            $adstats1['impressions'] += $ad['impressions'];
            $x ++;
        }

        if($adstats1['clicks']==''){
            $adstats1['clicks']=0;
        }
        if($adstats1['impressions']==''){
            $adstats1['impressions']=0;
        }

        if($adstats1) {

            $dsp = "<p>&nbsp;</p><table border='1' style='border-collapse: collapse; padding: 5px;' ><tr><th colspan='3' style='padding: 5px;'>" . $topic[0] . "</th></tr>";
            $dsp .= "<tr><td style='padding: 5px;'>Day</td><td style='padding: 5px;'>Clicks</td><td style='padding: 5px;'>Impressions</td></tr>";

            if ($adstats) {
               foreach ($adstats as $stat) {
                    $dsp .= "<tr><td style='padding: 5px;'>" . $stat['day'] . "</td><td style='padding: 5px;'>" . $stat['clicks'] . "</td><td style='padding: 5px;'>" . $stat['impressions'] . "</td></tr>";
                }
            }else {
                $dsp .="<tr><td colspan='3' style='padding: 5px;'><em> Data not Found </em></td></tr>";
            }
                $dsp .="</table>";

                if(isset($useremail)) {


                    if(get_option('dsp_subject')){
                        $subject = get_option('dsp_subject')." -" . $month . "/" . $year;

                    }else {
                        $subject = "Ad Report -" . $month . "/" . $year;
                    }

                    if(get_option('dsp_from')){
                        $from = get_option('dsp_from');

                    }else {
                        $from = "Adrotate Email Add-on";
                    }
                    if(get_option('dsp_from_email')){
                        $from_email = get_option('dsp_from_email');

                    }else {
                        $from_email = $email;
                    }
                    if(get_option('dsp_cc')){
                        $cc = get_option('dsp_cc');

                    }else {
                        $cc = $email;
                    }
                    if(get_option('dsp_reply')){
                        $reply = get_option('dsp_reply');

                    }else {
                        $reply = $email;
                    }

                    if(get_option('dsp_email_template')) {
                        $message = get_option('dsp_email_template');
                        $message = str_replace('{aduser}',$user[0]['display_name'],$message);
                        $message = str_replace('{adimpression}',$adstats1['impressions'],$message);
                        $message = str_replace('{adclick}', $adstats1['clicks'],$message);
                        $message = str_replace('{adreport}',$dsp,$message);
                    }else{
                        $url= $siteurl."/wp-admin/admin.php?page=adrotate_email&message='error'";
                        wp_redirect($url);
                    }

                    $headers = "MIME-Version: 1.0\n" .
                        "From: ".$from."<".$from_email.">\n" .
                        "Cc: ".$cc."\n";

                    $headers.= "Reply-To: ".$reply."\r\n\n".
                        "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";

                    if (wp_mail($useremail, $subject, $message, $headers)) {
                        $html = '<div class="updated">';
                        $html .= '<p>';
                        $html .= 'Your Email Sent Successfully to '.$user[0]['display_name'].'.';
                        $html .= '</p>';
                        $html .= '</div><!-- /.updated -->';
                        echo $html;
                    }else{
                        $html = '<div class="error">';
                        $html .= '<p>';
                        $html .= 'Your Email was not Sent to '.$user[0]['display_name'].'. please Try again.';
                        $html .= '</p>';
                        $html .= '</div><!-- /.updated -->';
                        echo $html;
                    }


                } else {
                    $html = '<div class="error">';
                    $html .= '<p>';
                    $html .= __( 'Please Add user to Ad for Send mail', 'add-rotate-email-addon' );
                    $html .= '</p>';
                    $html .= '</div><!-- /.updated -->';
                    echo $html;
                }

        }
    }
}