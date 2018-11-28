<template>
	<div>
		<!-- BEGIN: Left Aside -->
		<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
			<i class="la la-close"></i>
		</button>
		<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

			<!-- BEGIN: Aside Menu -->
			<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1"
			 m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
				<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
					<li class="m-menu__item  m-menu__item--active" aria-haspopup="true">
						<router-link to='/' class="m-menu__link ">
							<i class="m-menu__link-icon flaticon-line-graph"></i>
							<span class="m-menu__link-title">
								<span class="m-menu__link-wrap">
									<span class="m-menu__link-text">Trang chủ</span>
									<span class="m-menu__link-badge">
										<span class="m-badge m-badge--danger">2</span>
									</span>
								</span>
							</span>
						</router-link>
					</li>
					<li class="m-menu__section ">
						<h4 class="m-menu__section-text">Groups</h4>
						<i class="m-menu__section-icon flaticon-more-v3"></i>
					</li>
					<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
						<a href="javascript:;" class="m-menu__link m-menu__toggle">
							<i class="m-menu__link-icon la la-group"></i>
							<span class="m-menu__link-text">Group của bạn</span>
							<i class="m-menu__ver-arrow la la-angle-right"></i>
						</a>
						<div class="m-menu__submenu ">
							<span class="m-menu__arrow"></span>
							<ul class="m-menu__subnav">
								<li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
									<span class="m-menu__link">
										<span class="m-menu__link-text">Base</span>
									</span>
								</li>
								<li class="m-menu__item " aria-haspopup="true">
									<router-link to='/group/3/objectives' class="m-menu__link">
										<i class="m-menu__link-bullet m-menu__link-bullet--dot">
											<span></span>
										</i>
										<span class="m-menu__link-text">Group 3</span>
									</router-link>
								</li>
								<li class="m-menu__item " aria-haspopup="true">
									<router-link to='/group/4/objectives' class="m-menu__link ">
										<i class="m-menu__link-bullet m-menu__link-bullet--dot">
											<span></span>
										</i>
										<span class="m-menu__link-text">Doãn Văn Toản</span>
									</router-link>
								</li>
								<li class="m-menu__item " aria-haspopup="true">
									<router-link to='/group/5/objectives' class="m-menu__link ">
										<i class="m-menu__link-bullet m-menu__link-bullet--dot">
											<span></span>
										</i>
										<span class="m-menu__link-text">Phan Văn Thanh</span>
									</router-link>
								</li>
							</ul>
						</div>
					</li>

				</ul>
			</div>

			<!-- END: Aside Menu -->
		</div>
	</div>
</template>

<script>
	export default {
	    Name: 'LeftAside'
	}
</script>
