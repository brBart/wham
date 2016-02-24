<?php if (count($result) == 0) :?>
<i>- No results to display -</i>
<?php else :?>
<table class="table table-striped table-hover table-condensed<?= (count($result) > 10)?" example":"" ?>">
    <thead>
        <tr>
            <th><center>Name</center></th>
                <th class="hidden-phone"><center>Location</center></th>
                <th><center>No. of servers</center></th>
        </tr>
    </thead>
    <tbody>

            <?php foreach($result as $data) :?>
                <tr>
                    <td><center><a href="<?php echo site_url(); ?>/servers/listdcs/view/<?=$data->dc_id ?>/"><?=$data->dc_name ?></a></center></td>
                    <td class="hidden-phone"><center><a href="http://maps.google.com/maps?q=<?=$data->dc_location ?>" target="_blank"><?=$data->dc_location ?></a></center></td>
                    <td><center><?php echo ($data->count==0)?'-':"<a href='". site_url() . "/servers/listdcs/showservers/$data->dc_id/'>" . $data->count . '</a>' ; ?></center></td>
                </tr>
            <?php endforeach; ?>
    </tbody>
</table><p>&nbsp;</p>
<?php endif; ?>
<p>&nbsp;</p><hr/><p>&nbsp;</p>