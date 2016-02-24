<?php if (count($result) == 0) :?>
<i>- No results to display -</i>
<?php else :?>
<table class="display table table-striped table-hover table-condensed<?= (count($result) > 10)?" example":"" ?>">
    <thead>
        <tr>
            <th>Name</th>
            <th>Hostname</th>
            <th class="hidden-phone">Data center</th>
            <th class="hidden-phone">IP Address</th>
            <th class="hidden-phone">Control Panel</th>
            <th class="hidden-phone hidden-tablet">Rack</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($result as $data) :?>
        <tr>
            <td><a href="<?= site_url()?>/servers/viewserver/info/<?=$data->s_id ?>/"><?=$data->s_name ?></a> 
                            <?php if ($data->s_isactive == "N") :?><span class="label"><i>!</i></span><?php $show_legend = TRUE; ?><?php endif;?>
                            </td>
                        <td><a href="http://<?=$data->s_hostname ?>" target="_blank"><?=$data->s_hostname ?></a></td>
                        <td class="hidden-phone"><a href="<?= site_url()?>/servers/listdcs/view/<?=$data->dc_id ?>/"><?=$data->dc_name . ", " . $data->dc_location ?></a></td>
                        <td class="hidden-phone"><a href="http://<?=$data->s_ip ?>" target="_blank"><?=$data->s_ip ?></a></td>
                        <td class="hidden-phone">
                <?php if ($data->p_name == "CPanel/WHM") :?>        
                        <a href="<?= site_url() ?>/servers/viewserver/redirect/whm/<?=$data->s_id ?>/" target="_blank"><?=$data->p_name ?></a>
                <?php endif; ?>        
                        </td>
                        <td class="hidden-phone hidden-tablet"><?=$data->s_rack ?></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table><p>&nbsp;</p>
<?php endif; ?>
<p>&nbsp;</p><hr/><p>&nbsp;</p>