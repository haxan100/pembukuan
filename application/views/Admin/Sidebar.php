<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

	<div data-simplebar class="h-100">
		<?php
		$page = $header['page'];

		?>
		<style>
			.notification-badge {
				position: absolute;
				top: 5px;
				right: 10px;
				background-color: red;
				color: white;
				border-radius: 50%;
				padding: 5px 8px;
				font-size: 10px;
				min-width: 20px;
				text-align: center;
				line-height: 1;
				z-index: 10;
			}
		</style>

		<!--- Sidemenu -->
		<div id="sidebar-menu">
			<!-- Left Menu Start -->
			<ul class="metismenu list-unstyled" id="side-menu">

				<li class="menu-title">Main</li>

				<li class="<?= $page === 'dashboard' ? 'mm-active' : '' ?>">
					<a href="<?= base_url('Admin/index') ?>" class="waves-effect">
						<i class="ti-home"></i>
						<span>Dashboard</span>
					</a>
				</li>

				<?php if (check_access_sidebar('master_admin')): ?>
					<li class="<?= $page === 'master_admin' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('Admin/master_admin') ?>" class="waves-effect">
							<i class="ti-settings"></i>
							<span>Master Admin</span>
						</a>
					</li>
				<?php endif; ?>

				<?php if (check_access_sidebar('master_user')): ?>
					<li class="<?= $page === 'master_user' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('MasterUser/index') ?>" class="waves-effect">
							<i class="ti-user"></i>
							<span>Master User</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if (check_access_sidebar('master_role')): ?>
					<li class="<?= $page === 'master_role' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('Admin/master_role') ?>" class="waves-effect">
							<i class="ti-lock"></i>
							<span>Master Role</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if (check_access_sidebar('master_log')): ?>
					<li class="<?= $page === 'master_log' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('Admin/master_log') ?>" class="waves-effect">
							<i class="ti-lock"></i>
							<span>Log </span>
						</a>
					</li>
				<?php endif; ?>
				<?php if (check_access_sidebar('master_mitra')): ?>
					<li class="<?= $page === 'master_mitra' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('Admin/master_mitra') ?>" class="waves-effect">
							<i class="ti-lock"></i>
							<span>Master Mitra </span>
						</a>
					</li>

				<?php endif; ?>
				<?php if (check_access_sidebar('terms')): ?>
					<li class="<?= $page === 'terms' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('Admin/syarat_ketentuan') ?>" class="waves-effect">
							<i class="ti-lock"></i>
							<span>Syarat Dan Ketentuan </span>
						</a>
					</li>
				<?php endif; ?>
				<?php if (check_access_sidebar('slider')): ?>
					<li class="<?= $page === 'slider' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('Admin/slider') ?>" class="waves-effect">
							<i class="ti-lock"></i>
							<span>Slider </span>
						</a>
					</li>
				<?php endif; ?>
				<?php if (check_access_sidebar('setting')): ?>
					<li class="<?= $page === 'setting' ? 'mm-active' : '' ?>">
						<a href="<?= base_url('Admin/setting') ?>" class="waves-effect">
							<i class="ti-lock"></i>
							<span>Setting </span>
						</a>
					</li>
				<?php endif; ?>


				<!-- Tambahkan menu lainnya sesuai kebutuhan -->

			</ul>
		</div>
		<!-- Sidebar -->
	</div>
</div>
<!-- Left Sidebar End -->