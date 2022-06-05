<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<!-- Content -->
<table border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td><span class="preheader">{{preheadText}}</span>&nbsp;</td>
        <td class="container">
            <div class="content">

                <!-- START CENTERED WHITE CONTAINER -->
                <!-- START HEADER -->
                <div class="header">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="align-center">
                                <a href="<?=$host?>" target="_blank"><img
                                        src="https://localhost:8001/public/assets/img/logoKngell.png" width="70" height="41"
                                        alt="Logo" align="center"></a>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- END HEADER -->
                <table border="0" cellpadding="0" cellspacing="0" class="main">
                    <!-- START NOTIFICATION BANNER -->
                    <tr>
                        <td>
                            <table border="0" cellpadding="0" cellspacing="0" class="alert alert-success">
                                <tr>
                                    <td align="center"> {{msgTitle}} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- END NOTIFICATION BANNER -->

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <p>{{name}}</p>
                                        <p>{{msgBody}}</p>
                                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                        <table border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td> <a href="{{link}}"
                                                                            target="_blank">{{btnText}}</a> </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p>{{msgEnd}}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>

                <!-- START FOOTER -->
                <div class="footer">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-block">
                                <span class="apple-link">Company Inc, 3 Abbey Road, San Francisco CA 94102</span>
                                <br> Don't like these emails? <a href="http://htmlemail.io/blog">Unsubscribe</a>.
                            </td>
                        </tr>
                        <tr>
                            <td class="content-block powered-by">
                                Powered by <a href="http://htmlemail.io">HTMLemail</a>.
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- END FOOTER -->

                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
<!-- Fin Content -->
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?php $this->end();