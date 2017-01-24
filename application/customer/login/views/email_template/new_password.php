<html xmlns="http://www.w3.org/1999/xhtml"><head>
        <meta name="viewport" content="width=device-width">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <title>CREWGO </title>
                <style>
                    /* ------------------------------------- 
                                    GLOBAL 
                    ------------------------------------- */
                    * { 
                        margin:0;
                        padding:0;
                        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; 
                        font-size: 100%;
                        line-height: 1.6;
                    }

                    img { 
                        max-width: 100%; 
                    }

                    body {
                        -webkit-font-smoothing:antialiased; 
                        -webkit-text-size-adjust:none; 
                        width: 100%!important; 
                        height: 100%;
                    }


                    /* ------------------------------------- 
                                    ELEMENTS 
                    ------------------------------------- */
                    a { 
                        color: #348eda;
                    }

                    .btn-primary, .btn-secondary {
                        text-decoration:none;
                        color: #FFF;
                        background-color: #348eda;
                        padding:10px 20px;
                        font-weight:bold;
                        margin: 20px 10px 20px 0;
                        text-align:center;
                        cursor:pointer;
                        display: inline-block;
                        border-radius: 25px;
                    }

                    .btn-secondary{
                        background: #aaa;
                    }

                    .last { 
                        margin-bottom: 0;
                    }

                    .first{
                        margin-top: 0;
                    }


                    /* ------------------------------------- 
                                    BODY 
                    ------------------------------------- */
                    table.body-wrap { 
                        width: 100%;
                        padding: 20px;
                    }

                    table.body-wrap .container{
                        border: 1px solid #f0f0f0;
                    }


                    /* ------------------------------------- 
                                    FOOTER 
                    ------------------------------------- */
                    table.footer-wrap { 
                        width: 100%;	
                        clear:both!important;
                    }

                    .footer-wrap .container p {
                        font-size:12px;
                        color:#666;

                    }

                    table.footer-wrap a{
                        color: #999;
                    }


                    /* ------------------------------------- 
                                    TYPOGRAPHY 
                    ------------------------------------- */
                    h1,h2,h3{
                        font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
                        margin: 40px 0 10px;
                        line-height: 1.2;
                        font-weight:200; 
                    }

                    h1 {
                        font-size: 36px;
                    }
                    h2 {
                        font-size: 28px;
                    }
                    h3 {
                        font-size: 22px;
                    }

                    p, ul { 
                        margin-bottom: 10px; 
                        font-weight: normal; 
                        font-size:14px;
                    }

                    ul li {
                        margin-left:5px;
                        list-style-position: inside;
                    }

                    /* --------------------------------------------------- 
                                    RESPONSIVENESS
                                    Nuke it from orbit. It's the only way to be sure. 
                    ------------------------------------------------------ */

                    /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                    .container {
                        display:block!important;
                        max-width:600px!important;
                        margin:0 auto!important; /* makes it centered */
                        clear:both!important;
                    }

                    /* This should also be a block element, so that it will fill 100% of the .container */
                    .content {
                        padding:20px;
                        max-width:600px;
                        margin:0 auto;
                        display:block; 
                    }

                    /* Let's make sure tables in the content area are 100% wide */
                    .content table { 
                        width: 100%; 
                    }

                </style>
                </head>

                <body bgcolor="#f6f6f6">

                    <!-- body -->
                    <table class="body-wrap">
                        <tbody>
                            <tr>
                                <td></td>
                                <td class="container" bgcolor="#FFFFFF">

                                    <!-- content -->
                                    <div class="content">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                      <img src="<?php echo base_url('assets/images/logo.png')?>" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br />
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p>Hi <?php echo ucwords($username); ?>,</p>
                                                        <p>Welcome to CrewGo! </p>
                                                        <p>Your password has been reset successfully now you can login with the password below and change it later
                                                            <br />
                                                            Password : <strong><?php echo $password; ?></strong>
                                                        </p>
                                                        <p>Thank You.</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /content -->

                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- /body -->

                    <!-- footer -->
                    <table class="footer-wrap">
                        <tbody>
                            <tr>
                                <td></td>
                                <td class="container">

                                    
                                    <div class="content">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                        <p>CREWGO.
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>  

                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- /footer -->


                </body>
</html>