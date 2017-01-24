<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo 'Fratpak'; ?></title>
    </head>
    <body>
        <table cellpadding="0" cellspacing="0" border="0" align="center">
            <tr <?php echo isset($content) ? 'align="center"' : '' ?> ><td>
                    <?php if (isset($content)): ?>
                        <?php echo $content ?>
                    <?php else: ?>

                        <?php echo form_open('', 'autocomplete="off"'); ?>
                        <?php echo $message; ?>
                        <p>
                            <label for="new_passcode">New Passcode: </label> 
                            <?php echo form_input($new_passcode); ?>
                        </p>
                        <p>
                            <label for="new_passcode">Confirm Passcode: </label> 
                            <?php echo form_input($new_passcode_confirm); ?>
                        </p>

                        <p><?php echo form_submit('submit', "Submit"); ?></p>

                        <?php echo form_close(); ?>
                    <?php endif; ?>

                </td></tr>
        </table>

    </body>
</html>
