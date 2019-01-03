<?php /*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Footer for each page
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

if (!isset($noDisplayMenu))
{
    echo "<div class='mainheadermenu'>
        <table width='90%' style='border:0'>
        <tr>
            <td class='menuitem'>
                <a class='menuitem' href='". $BASE_urlpath ."/base_ag_main.php?ag_action=list'>". _AGMAINT."</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a class='menuitem' href='". $BASE_urlpath ."/base_maintenance.php'>". _CACHE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
    if ($Use_Auth_System == 1)
    {
        echo("<a class='menuitem' href='". $BASE_urlpath ."/base_user.php'>". _USERPREF ."</a>&nbsp;&nbsp;|&nbsp;&nbsp;");
        echo("<a class='menuitem' href='". $BASE_urlpath ."/base_logout.php'>". _LOGOUT ."</a>&nbsp;&nbsp;|&nbsp;&nbsp;");
    }
    
    echo "<a class='menuitem' href='". $BASE_urlpath ."/admin/index.php'>". _ADMIN ."</a>
            </td>
        </tr>
    </table>
    </div>";
}      
?>


<div class="mainfootertext">
    <a class="largemenuitem" href="http://base.secureideas.net" target="_new">BASE</a> <?php echo $BASE_VERSION . _FOOTER; ?>
</div>
<br />
