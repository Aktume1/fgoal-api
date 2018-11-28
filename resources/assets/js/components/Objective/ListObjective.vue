<template>
	<div>
		<!-- BEGIN: Subheader -->
		<div class="m-subheader ">
			<div class="d-flex align-items-center">
				<div class="mr-auto">
					<h3 class="m-subheader__title ">List Objective</h3>
				</div>
				
			</div>
		</div>

		<!-- END: Subheader -->
		<div class="m-content">
			<div class="row">
				<div class="col-6">
					<div class="m-portlet">
						<div class="m-portlet__body  m-portlet__body--no-padding">
							<div class="row m-row--no-padding m-row--col-separator-xl">
								<div class="col-xl-12">
									<!--begin:: Widgets/Profit Share-->
									<div class="m-widget14">
										<div class="m-widget14__header">
											<h3 class="m-widget14__title">
												Tiến độ hàng tuần
											</h3>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6">
					<!--Begin::Section-->
					<div class="m-portlet">
						<div class="m-portlet__body  m-portlet__body--no-padding">
							<div class="row m-row--no-padding m-row--col-separator-xl">

								<div class="col-12">
									<!--begin:: Widgets/Profit Share-->
									<div class="m-widget14">
										<div class="m-widget14__header">
											<h3 class="m-widget14__title">
												Summary Status
											</h3>
										</div>
										<div class="row  align-items-center">
											<div class="col">
												<div id="m_chart_profit_share" class="m-widget14__chart" style="height: 160px">
													<svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-donut"
													 style="width: 100%; height: 100%;">
														<g class="ct-series custom">
															<path d="M144.202,108.314A66.5,66.5,0,0,0,84.031,13.5" class="ct-slice-donut" ct:value="32" ct:meta="{&amp;quot;data&amp;quot;:{&amp;quot;color&amp;quot;:&amp;quot;#716aca&amp;quot;}}"
															 style="stroke-width: 17px;" stroke-dasharray="133.70703125px 133.70703125px" stroke-dashoffset="-133.70703125px"
															 stroke="#716aca">
																<animate attributeName="stroke-dashoffset" id="anim0" dur="1000ms" from="-133.70703125px" to="0px" fill="freeze"
																 stroke="#716aca" calcMode="spline" keySplines="0.23 1 0.32 1" keyTimes="0;1"></animate>
															</path>
														</g>
														<g class="ct-series custom">
															<path d="M32.792,122.389A66.5,66.5,0,0,0,144.301,108.104" class="ct-slice-donut" ct:value="32" ct:meta="{&amp;quot;data&amp;quot;:{&amp;quot;color&amp;quot;:&amp;quot;#00c5dc&amp;quot;}}"
															 style="stroke-width: 17px;" stroke-dasharray="133.94052124023438px 133.94052124023438px"
															 stroke-dashoffset="-133.94052124023438px" stroke="#00c5dc">
																<animate attributeName="stroke-dashoffset" id="anim1" dur="1000ms" from="-133.94052124023438px" to="0px"
																 fill="freeze" stroke="#00c5dc" begin="anim0.end" calcMode="spline" keySplines="0.23 1 0.32 1" keyTimes="0;1"></animate>
															</path>
														</g>
														<g class="ct-series custom">
															<path d="M84.031,13.5A66.5,66.5,0,0,0,32.94,122.567" class="ct-slice-donut" ct:value="36" ct:meta="{&amp;quot;data&amp;quot;:{&amp;quot;color&amp;quot;:&amp;quot;#ffb822&amp;quot;}}"
															 style="stroke-width: 17px;" stroke-dasharray="150.6540985107422px 150.6540985107422px"
															 stroke-dashoffset="-150.6540985107422px" stroke="#ffb822">
																<animate attributeName="stroke-dashoffset" id="anim2" dur="1000ms" from="-150.6540985107422px" to="0px"
																 fill="freeze" stroke="#ffb822" begin="anim1.end" calcMode="spline" keySplines="0.23 1 0.32 1" keyTimes="0;1"></animate>
															</path>
														</g>
													</svg>
												</div>
											</div>
											<div class="col">
												<div class="m-widget14__legends">
													<div class="m-widget14__legend">
														<span class="m-widget14__legend-bullet m--bg-accent"></span>
														<span class="m-widget14__legend-text">37% Sport Tickets</span>
													</div>
													<div class="m-widget14__legend">
														<span class="m-widget14__legend-bullet m--bg-warning"></span>
														<span class="m-widget14__legend-text">47% Business Events</span>
													</div>
													<div class="m-widget14__legend">
														<span class="m-widget14__legend-bullet m--bg-brand"></span>
														<span class="m-widget14__legend-text">19% Others</span>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!--end:: Widgets/Profit Share-->
								</div>
							</div>
						</div>
					</div>
					<!--End::Section-->
				</div>
			</div>
			<div class="row">
				<div class="col-12">

					<div class="m-portlet">
						<div class="m-portlet__body  m-portlet__body--no-padding">
							<div class="row m-row--no-padding m-row--col-separator-xl">
								<div class="col-12">
									<!--begin:: Widgets/Profit Share-->
									<div class="m-widget14">
										<div class="m-widget14__header">
											<h3 class="m-widget14__title">
												Mục tiêu
											</h3>
										</div>

										<div class="row align-items-center">
											<div class="col">
												<div class="list-objective ml-2">
													<div class="row">
														<div class="col-3">
															<h6 class="name-objective">Training 200 trainee</h6>	
														</div>

														<div class="col-3">
															<div class="progress">
																<div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
															</div>	
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
	    name: 'ListObjective',
	}
</script>
