<table class="body" style="width:100%; color:#000;">
    <tr>
        <td class="center" align="center" valign="top">
            <center>

                <table class="container main-content" style="width:580px;">
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                <tr>
                                    <td align="center">
                                        <img src="<?php echo base_url('assets/images/logo.png') ?>"/>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <br/>
                            <table class="row" style="width:100%;">
                                <tr style="text-align:center; width:100%;">
                                    <td class="wrapper last no-pad-top">

                                        <table class="twelve columns" style="width:100%;">
                                            <tr class="email-heading">
                                                <td class="center text-pad">
                                                    <center>
                                                        <h1 style="font-size: 32px;    font-weight: bold;    text-align: center;    padding:15px 0;">
                                                            CREWGO Password Recovery </h1>
                                                    </center>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                                <tr style="background:#fff; border-top:#fff 1px solid;">
                                    <td class="wrapper last pad-top-32">

                                        <table class="twelve columns">
                                            <tr class="email-content">
                                                <td class="expander">
                                                    <p>Hi <?php echo ucwords($name); ?>,</p>
                                                        <p>Welcome to CrewGo! </p>
                                                        <p>To reset your password visit the link below:</p>
                                                        <p><?php
                                                           $url = base_url('customer/login/complete_forgotten_password/?user=' . $user_id . '/&code=' . $code);
                                                            echo anchor($url, 'click here');
                                                            ?></p>
                                                        <p>Thank You.</p>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </center>
        </td>
    </tr>
</table>