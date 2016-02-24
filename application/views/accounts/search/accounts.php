<?php if (count($result) == 0) :?>
<i>- No results to display -</i>
<?php else :?>
     <table class="table table-hover table-striped table-condensed display<?= (count($result) > 10)?" example":"" ?>">
	<thead>
            <tr><th>User</th><th>Domain</th><th class="hidden-phone">Server</th><th class="hidden-phone">IP Address</th><th class="hidden-phone hidden-tablet">Email</th><th class="hidden-phone">Plan</th><th class="hidden-tablet hidden-phone">Owner</th><th>Action</th></tr>
	</thead>
        <tbody>
      <?php foreach($result as $account) :?>
            <tr style="text-align:center">
                <td><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $account->server_id ?>/<?= $account->user ?>/"><?= $account->user ?></a></td>
                <td><a href="http://<?= $account->domain ?>" target="_blank"><?= $account->domain ?></a></td>
                <td class="hidden-phone"><a href="<?= site_url() ?>/servers/viewserver/info/<?= $account->server_id ?>/"><?= $account->s_name ?></a></td>
                <td class="hidden-phone"><a href="http://<?= $account->ip ?>" target="_blank"><?= $account->ip ?></a></td>
                <td class="hidden-phone hidden-tablet"><?= $account->email ?></td>
                <td class="hidden-phone"><?= $account->plan ?></td>
                <td class="hidden-tablet hidden-phone"><?php if ($account->owner == "root") :?><?= $account->owner ?><?php else :?><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $account->server_id ?>/<?= $account->owner ?>/"><?= $account->owner ?></a><?php endif; ?></td>
                <td><a href="<?= site_url() ?>/servers/viewserver/whm_modifyacct/<?= $account->server_id ?>/<?= $account->user ?>/"><i class="icon-pencil"></i></a>&nbsp;<a href="<?= site_url() ?>/servers/viewserver/whm_removeacct/<?= $account->server_id ?>/<?= $account->user ?>/"><i class="icon-remove"></i></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
     </table><p>&nbsp;</p>
<?php endif; ?><p>&nbsp;</p><hr/><p>&nbsp;</p>
