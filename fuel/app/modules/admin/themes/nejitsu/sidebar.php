<ul class="nav">
    <!-- Main menu -->
    <li class="<?php echo $is_active('dashboard'); ?>"><a href="/admin"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
    <li class="<?php echo $is_active('images'); ?>"><a href="/admin/images"><i class="glyphicon glyphicon-list"></i> Images</a></li>
    <li class="<?php echo $is_active('hashes'); ?>"><a href="/admin/hashes"><i class="glyphicon glyphicon-list"></i> Hashes</a></li>
    <li class="<?php echo $is_active('ips'); ?>"><a href="#"><i class="glyphicon glyphicon-list"></i> NG Ips</a></li>
    <li class="<?php echo $is_active('words'); ?>"><a href="#"><i class="glyphicon glyphicon-list"></i> NG Words</a></li>
    <li class="<?php echo $is_active('users'); ?>"><a href="/admin/users"><i class="glyphicon glyphicon-list"></i> Users</a></li>
    <li "><a href="/admin/login"><i class="glyphicon glyphicon-record"></i> Logout</a></li>
</ul>

