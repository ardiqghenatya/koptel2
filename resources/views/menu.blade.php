<ul class="sidebar-menu">
    <li>
        <a href="{{ url() }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ url('barcodes') }}">
            <i class="fa fa-dashboard"></i> <span>Pendaftaran</span>
        </a>
    </li>
    <li>
        <a href="{{ url('shelves') }}">
            <i class="fa fa-dashboard"></i> <span>Lemari</span>
        </a>
    </li>
    <li>
        <a href="{{ url('barcodeProcesses') }}">
            <i class="fa fa-dashboard"></i> <span>Penyimpanan</span>
        </a>
    </li>
    
    <!-- <li class="treeview">
        <a href="#">
            <i class="fa fa-table"></i> <span>Point Rewards </span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('rewardTypes') }}"><i class="fa fa-angle-double-right"></i> Reward Types</a></li>
            <li><a href="{{ url('rewardLogs') }}"><i class="fa fa-angle-double-right"></i> Reward Logs</a></li>
        </ul>
    </li> -->
</ul>