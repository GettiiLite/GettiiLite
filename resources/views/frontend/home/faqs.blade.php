<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Gettii Lite | FAQs</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="css/bootstrap3-wysiwyg/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="css/font-awesome/css/all.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="css/Login.css">
	<link rel="stylesheet" href="css/Main.min.css">
	<link rel="stylesheet" href="css/faqs.css">



	<!-- Google Font -->
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- =============================================== -->

<body>
	<!-- login-section -->
	<section class="faqs-section wrapper">
		<!-- login-container -->
		<div class="faqs-container">
			<!-- login-wrap-->
			<div class="faqs-wrap">
				<!-- title-->
				<div class="logo-wrap logo-wrap-fix">
					<div class="logo-wrap-image"><img src="assets/images/logo/logo.png" alt="IMG"></div>
					<div class="faqs-title">{{trans('home.S_About')}}</div>
				</div>

				<!-- /.title-->
				<!-- content-->
				<div class="">
					<div class="row">
						<!-- left nav -->
						<div class="col-lg-4">

							<!--  -->
							<div class="nav nav-pills faq-nav" id="faq-tabs" role="tablist" aria-orientation="vertical">
								<a href="#tab1" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab1"
									aria-selected="true">
									<i class="fas fa-building"></i> 公司介紹
								</a>
								<a href="#tab2" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab2"
									aria-selected="false">
									<i class="fas fa-cogs"></i> 技術支援
								</a>
								<a href="#tab3" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab3"
									aria-selected="false">
									<i class="fas fa-user-cog"></i>活動建置功能管理相關
								</a>
								<a href="#tab4" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab4"
									aria-selected="false">
									<i class="fas fa-comments-dollar"></i> 帳務相關問題
								</a>
								<a href="#tab5" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab5"
									aria-selected="false">
									<i class="fas fa-user-edit"></i> 註冊申請相關問題
								</a>
								<a href="#tab6" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab6"
									aria-selected="false">
									<i class="fas fa-ticket-alt"></i> 購票相關
								</a>
								<a href="#tab7" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab7"
									aria-selected="false">
									<i class="fas fa-user-lock"></i> 登入或忘記密碼相關
								</a>
								<a href="#tab8" class="faq-nav-link" data-toggle="pill" role="tab" aria-controls="tab8"
									aria-selected="false">
									<i class="fas fa-info-circle"></i> 其他
								</a>
							</div>
						</div>
						<!-- /.left nav -->
						<div class="col-lg-8">
							<div class="tab-content" id="faq-tab-content">
								<!-- tab1 tab-pane show active-->
								<div class="tab-pane active" id="tab1" role="tabpanel" aria-labelledby="tab1">
									<div class="accordion" id="accordion-tab-1">
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-1-heading-1">
												<h3 class="box-title"><b>Q1</b>可以介紹一下你們公司嗎 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>我們是智林國際(股)，英文名稱是Link Station Taiwan。是日商Link Station(株)在台灣分公司。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-1-heading-2">
												<h3 class="box-title"><b>Q2</b>你們的營業時間是幾點呢 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>營業時間為周一至周五，上午九點至下午五點半。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-1-heading-3">
												<h3 class="box-title"><b>Q3</b>你們公司的服務到底是什麼 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<ol>
															<li>我們是專業開發SaaS型「售票系統」的公司，提供給有售票需求的公司行號、活動單位、個人團體，簡單快速，自由度極高的售票系統。
															</li>

															<li>我們整合售票過程所需的各式系統及金流服務，從售票活動建置、消費者購取票、付款金流的全方位服務。</li>
														</ol>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-1-heading-4">
												<h3 class="box-title"><b>Q4</b>你們公司有什麼產品 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<ol>
															<li>Gettii Lite：專業售票系統(銷售後台)</li>
															<li>專業售票平台(銷售前台)</li>
															<li>MOBAPASS：專業電子票券管理APP</li>
														</ol>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-1-heading-5">
												<h3 class="box-title"><b>Q5</b>產品的優勢是 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>Gettii Lite有三大優勢</p>
														<ol>
															<li>SaaS型售票系統，透過雲端連線系統便可建置活動，大幅降低主辦單位的硬體成本。</li>
															<li>系統更新及維護皆由我們負責，無須負擔額外費用。</li>
															<li>使用門檻低，無須事前繳納高額串接設定等費用。</li>
														</ol>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-1-heading-6">
												<h3 class="box-title"><b>Q6</b>免下載、免安裝是什麼意思 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>Gettii Lite是透過雲端即可使用的服務。不需下載任何程式及安裝，只需要在連網環境即可使用。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->

									</div>
								</div>
								<!-- /.tab1 -->
								<!-- tab2 -->
								<div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="tab2">
									<div class="accordion" id="accordion-tab-2">
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-2-heading-1">
												<h3 class="box-title"><b>Q1</b>什麼是SaaS型 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>

														<ol>
															<li>「SaaS」全名為「Software as a Service」。</li>
															<li>
																SaaS最大的特色在於軟體本身並沒有被下載到用戶的硬盤，而是儲存在提供商的雲端或者伺服器。對比傳統軟體需要花錢購買，下載。軟體即服務只需要用戶租用軟體，在線使用，不僅大大減少了用戶購買風險也無需下載軟體本身，無裝置要求的限制。
															</li>
														</ol>
														<small> <span style="font-size: 1em; color: darkorange;"><i
																	class="fas fa-lightbulb"></i></span>
															資料來源wikipedia</small>

													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-2-heading-2">
												<h3 class="box-title"><b>Q2</b>我們公司沒有技術人員，如果系統有更新或是維修該怎麼辦 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>後續的系統更新及維護都由我們服務，無須支付其他費用。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-2-heading-3">
												<h3 class="box-title"><b>Q3</b>我們公司有會員機制，用你們的系統可以串聯嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>貴單位如需將會員與Gettii Lite系統串聯時，我們將提供系統整合服務。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-2-heading-4">
												<h3 class="box-title"><b>Q4</b>作業環境需求？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>

														<ol>
															<li>可連網之電腦設備(MAC、Windows皆支援)，使用Gettii Lite需在連網環境下</li>
															<li>建議最低解析度：
																<ul>
																	<li>Gettii Lite : 1024*768</li>
																	<li>銷售網站 : 400*800 (Mobile)</li>
																</ul>
															</li>
															<li>瀏覽器需開啟Java Script與Cookie功能</li>

														</ol>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-2-heading-5">
												<h3 class="box-title"><b>Q5</b>操作上遇到困難該怎麼辦？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>

														<p>您可隨時致電給我們886-2-2627-1939或是Email至客服信箱info@linkst-tw.com</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-1-heading-1">
												<h3 class="box-title"><b>Q6</b>消費者在購票過程中若有問題該怎麼辦？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>

														<p>
															很抱歉，為保障三方權益及避免消費者混淆，建議您可先與消費者做初步連繫並排除障礙。

														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
									</div>
								</div>
								<!-- /.tab2 -->
								<!-- tab3 -->
								<div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="tab3">
									<div class="accordion" id="accordion-tab-3">
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-1">
												<h3 class="box-title"><b>Q1</b>我想使用Gettii Lite，該如何開始呢 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>

														<ul class="non">

															<li>步驟 ❶ 請先於Gettii Lite網站完成線上註冊後至「文件下載」中下載「法人/個人服務申請表」，
																填妥文件後後連同「營利事業登記證(影本)」或「身分證正反面影本」，掛號郵寄至「台北市內湖區洲子街79號9樓之1
																智林國際股份有限公司 業務部收」</li>
															<li>步驟 ❷ 收到申請後專員會與您聯繫，並開始進行審查作業。審查時間約15個工作天。</li>
															<li>步驟 ❸ 審查通過後您可立刻開始使用Gettii Lite的服務。</li>
														</ul>
														<p> <i class="fas fa-arrow-circle-right"></i>
															或是直接致電或Email給我們，專員收到將會立即與您聯繫，並協助您完成註冊服務。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-2">
												<h3 class="box-title"><b>Q2</b>所以我可以在你們的「售票系統」操作什麼？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>您可以透過系統自由配置想要的售票內容。</p>
														<p>例如：票價設定(0元票、千元票)；票種設定(公關票、雙人套票)；早鳥票售票日期、活動上線日期；座位配置等。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-3">
												<h3 class="box-title"><b>Q3</b>我沒有架設官網，該怎麼讓消費者買到票呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>我們的售票系統屬於雲端服務，只需要提供URL網址連結，您的消費者便能買到票。
														</p>
														<small><span style="font-size: 1em; color: darkorange;"><i
																	class="fas fa-lightbulb"></i></span>
															需先在購票網站完成註冊步驟</small>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-4">
												<h3 class="box-title"><b>Q4</b>承上，所以刊登在售票網站上的內容是誰會幫忙編輯呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>Gettii Lite與售票網站間有自動串聯，您於Gettii Lite上輸入的資料會自動更新至售票網站上。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-5">
												<h3 class="box-title"><b>Q5</b>所謂的專屬售票平台是什麼？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>Gettii Lite是您的票券管理後台，而銷售前台就是您的專屬售票網站。</p>
														<p>只要使用Gettii Lite的商家都配有專屬售票網站，售票網站內的活動資訊便是您在Gettii
															Lite上建置的活動。消費者只要註冊售票網站便能簡單快速的購買到票券。"
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-6">
												<h3 class="box-title"><b>Q6</b>系統只有一組帳號密碼嗎？如果公司有其他人也會使用系統的話該怎麼辦？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>每間廠商各有一組主帳號並可設定子帳號給他人使用，擁有子帳號的操作者可任意編輯或檢視活動內容。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-7">
												<h3 class="box-title"><b>Q7</b>最多可以設定幾組後台管理子帳號？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>上限10組。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-8">
												<h3 class="box-title"><b>Q8</b>部分資料我不想讓子帳號的操作者看到怎麼辦？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>針對每個管理子帳號可設定不同的管理及檢視權限，並設定子帳號的使用期限。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-9">
												<h3 class="box-title"><b>Q9</b>售票中的活動如果取消的話會有什麼費用嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>依照您所選擇的合作方案可能會收取部分費用。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-10">
												<h3 class="box-title"><b>Q10</b>可以請你們幫忙建置活動嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>很抱歉，目前不提供此服務。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-11">
												<h3 class="box-title"><b>Q11</b>我們公司沒有印票機器，該怎麼辦呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>我司提供的取票方式有兩種。</p>
														<ol>
															<li>統一超商內之ibon機票。</li>
															<li>MOBAPASS電子票券(需下載APP)。</li>
														</ol>
														<hr>
														<p><span style="font-size: 1em; color: darkorange;"><i class="fas fa-lightbulb"></i></span>
															MOBAPASS是什麼 ?
														</p>
														<ul>
															<li> MOBAPASS是電子票券專用APP，支援iOS及Android。</li>
															<li>每台智慧型裝置配有一組專屬序號。(若更換裝置需進行序號移轉)
																當消費者完成購票程序後，系統會自動將購買的票券存入MOBAPASS中，入場時出示MOBAPASS中的QR
																CODE票券由工作人員驗證入場。</li>
															<li>同時MOBAPASS設有票券轉讓功能，可將票券轉給同為使用MOBAPASS之裝置用戶。</li>
														</ul>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-12">
												<h3 class="box-title"><b>Q12</b>付款方式有哪些呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>線上信用卡付款(支援VISA、Mastercard、JCB)、統一便利商店(7-11)。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-13">
												<h3 class="box-title"><b>Q13</b>我想使用Gettii Lite，該如何開始呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<ol>
															<li>請先於Gettii Lite網站完成線上註冊後至「文件下載」中下載「法人/個人服務申請表」，
																填妥文件後後連同「營利事業登記證(影本)」或「身分證正反面影本」，掛號郵寄至「台北市內湖區洲子街79號9樓之1
																智林國際股份有限公司 業務部收」</li>
															<li>收到申請後專員會與您聯繫，並開始進行審查作業。審查時間約15個工作天。</li>
															<li>審查通過後您可立刻開始使用Gettii Lite的服務。</li>
														</ol>
														<p><i class="fas fa-arrow-circle-right"></i>
															或是直接致電或Email給我們，專員收到將會立即與您聯繫，並協助您完成註冊服務。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-14">
												<h3 class="box-title"><b>Q14</b>可以發送電子報給我的會員嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>很抱歉，目前並無提供電子報派送服務。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-15">
												<h3 class="box-title"><b>Q15</b>已經公開(發布)的活動還可以修改內容嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>發布後仍可修改部分內容，但為保障購票者權益及服務品質，部分內容將無法修改。</p>
														<p> 若有任何疑慮，您可致電給我們886-2-2627-1939或是Email至客服信箱info@linkst-tw.com
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-16">
												<h3 class="box-title"><b>Q16</b>Gettii Lite可以在手機或平板上運行嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>不建議使用，部分功能可能無法正常顯示及操作。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-17">
												<h3 class="box-title"><b>Q17</b>建置的活動有人數或場次的上限嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>不管是千人入場的大型展覽演唱會、親友團應援的私人成果發表，無人數限制，無場地限制，無場次限制！</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-18">
												<h3 class="box-title"><b>Q18</b>多樣化場次座位安排是指什麼？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>您可自由編排活動場地的座位(固定式座椅之場地除外)。</p>
														<p>以演唱會為例，您可安排A區為無劃位之搖滾區域，B區為全指定席之區域。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-19">
												<h3 class="box-title"><b>Q19</b>關於活動的種類有限制嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>Gettii Lite支援各種類活動。</p>
														<p>無論是演唱會；展覽會；運動賽事；舞台劇等都沒問題。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-20">
												<h3 class="box-title"><b>Q20</b>我只是學生想舉辦成果發表會也可以使用你們系統嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>當然可以，Gettii Lite支援各式各樣的活動，只要您有售票需求都可以使用。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-21">
												<h3 class="box-title"><b>Q21</b>我想舉辦的是有固定(劃位)座位的活動也可以嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>沒有問題。無論是要劃位的演話劇活動，或是自由入座的展覽會活動Gettii Lite都可以對應。</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-3-heading-22">
												<h3 class="box-title"><b>Q22</b>系統大約多久會自動登出呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>基於安全性考量，在未使用系統的情況下，會於2小時後自動登出。建議每編輯到一個段落後按下儲存鍵，以免輸入過的資料遺失。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
									</div>
								</div>
								<!-- /.tab3 -->
								<!-- tab4 -->
								<div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="tab4">
									<div class="accordion" id="accordion-tab-4">
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-4-heading-1">
												<h3 class="box-title"><b>Q1</b>那我會有購票者清單明細嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>您可隨時至系統上確認票券即時銷售狀況，其中包含購票者基本資料。同時也能匯出成EXCEL檔案。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-4-heading-2">
												<h3 class="box-title"><b>Q2</b>有代報娛樂稅的服務嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>我們沒有提供代報娛樂稅服務，請自行申報。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-4-heading-3">
												<h3 class="box-title"><b>Q3</b>有電子發票的功能嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>目前無提供電子發票。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-4-heading-4">
												<h3 class="box-title"><b>Q4</b>可以開立二聯式及三聯式發票嗎?？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>可以的。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-4-heading-5">
												<h3 class="box-title"><b>Q5</b>有提供對帳表嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>有的，我們提供EXCEL格式的對帳表。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-4-heading-6">
												<h3 class="box-title"><b>Q6</b>銷售統計表是否能輸出呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>有的，可下載CSV檔案格式報表。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-4-heading-7">
												<h3 class="box-title"><b>Q7</b>你們結算週期是怎麼計算呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>歡迎您致電給我們886-2-2627-1939或是Email至客服信箱info@linkst-tw.com，將會由專員主動與您聯繫。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
									</div>
								</div>
								<!-- /.tab4 -->
								<!-- tab5 -->
								<div class="tab-pane" id="tab5" role="tabpanel" aria-labelledby="tab5">
									<div class="accordion" id="accordion-tab-5">



										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-5-heading-1">
												<h3 class="box-title"><b>Q1</b>註冊審查完成後，多久可以開始使用系統呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>審查通過會發送通知Email或電話通知，便可立刻開始使用Gettii Lite服務。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-5-heading-2">
												<h3 class="box-title"><b>Q2</b>公司(法人)註冊需要什麼文件？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<ol>
															<li>請在完成線上註冊後將 ❶Gettii Lite 服務申請表(法人) ❷營利事業登記證影本 ❸存摺影本，
																掛號郵寄至「台北市內湖區洲子街79號9樓之1 智林國際股份有限公司 業務部收」
																收到申請後將會由專員與您聯繫。</li>
															<li>或是直接致電或Email給我們，專員收到將會盡快與您聯繫。</li>
														</ol>
														<small><span style="font-size: 1em; color: darkorange;"><i
																	class="fas fa-lightbulb"></i></span>
															請至「文件下載」下載相關文件。
														</small>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-5-heading-3">
												<h3 class="box-title"><b>Q3</b>個人註冊需要什麼文件？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<ol>
															<li>請在完成線上註冊後將 ❶Gettii Lite 服務申請表(個人) ❷身分證正反面影本
																❸存摺影本，掛號郵寄至「台北市內湖區洲子街79號9樓之1 智林國際股份有限公司 業務部收」
																收到申請後將會由業務單位與您聯繫。</li>
															<li>您也可以直接致電或Email(info@linkst-tw.com)給我們，專員收到將會立即與您聯繫。</li>
														</ol>
														<small><span style="font-size: 1em; color: darkorange;"><i
																	class="fas fa-lightbulb"></i></span>
															請至「文件下載」下載相關文件。
														</small>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-5-heading-4">
												<h3 class="box-title"><b>Q4</b>註冊審查要多久時間？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>收到您的申請後將會由專員與您聯繫，審查時間約15個工作天。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-5-heading-5">
												<h3 class="box-title"><b>Q5</b>我的預算不多，你們的費用怎麼計算？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>我們提供0元及年租方案，您可依照需求進行選擇。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-5-heading-6">
												<h3 class="box-title"><b>Q6</b>你們有提供免費試用的服務嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>我們有提供系統測試環境，您可以提出申請試用。</p>
														<p>歡迎您致電給我們886-2-2627-1939或是Email至客服信箱info@linkst-tw.com，將會由專員主動與您聯繫。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
									</div>
								</div>
								<!-- /.tab5 -->
								<!-- tab6 -->
								<div class="tab-pane" id="tab6" role="tabpanel" aria-labelledby="tab6">
									<div class="accordion" id="accordion-tab-6">
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-6-heading-1">
												<h3 class="box-title"><b>Q1</b>可以詳細說明如何讓消費者買到票嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>當您利用Gettii Lite建立活動並開始售票後，消費者便可前往專屬售票網站購買票券。
														</p>
														<small><span style="font-size: 1em; color: darkorange;"><i
																	class="fas fa-lightbulb"></i></span>
															購票前需先註冊為售票網站會員。</small>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-6-heading-2">
												<h3 class="box-title"><b>Q2</b>那消費者在售票網站上完成購票後怎麼取票呢？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>目前我們提供兩種取票方式
														</p>
														<ol>
															<li>統一超商內的ibon機台取票付款及櫃台取票服務。</li>
															<li>使用我們的電子票券管理APP「MOBAPASS」取得電子票券。</li>
														</ol>
														<p>《 <span style="font-size: 1em; color: darkorange;"><i
																	class="fas fa-lightbulb"></i></span>關於MOBAPASS詳細說明請見下方
															》</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-6-heading-3">
												<h3 class="box-title"><b>Q3</b>使用MOBAPASS，活動現場該怎麼驗票？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>MOBAPASS採用特殊技術，現場工作人員驗票時無須使用任何機器設備，主辦單位也無須負擔軟硬體等成本。</p>
														<p>若欲瞭解更多歡迎隨時致電給我們886-2-2627-1939或是Email至客服信箱info@linkst-tw.com
														</p>

													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-6-heading-4">
												<h3 class="box-title"><b>Q4</b>MOBAPASS是什麼？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>

														<p> MOBAPASS是電子票券專用APP，支援iOS及Android。</p>
														<p>每台智慧型裝置配有一組專屬序號。(若更換裝置需進行序號移轉)
															當消費者完成購票程序後，系統會自動將購買的票券存入MOBAPASS中(購票完成後約需10分鐘的傳輸時間)，入場時出示MOBAPASS中的QR
															CODE票券由工作人員驗證入場。</p>
														<p>同時，MOBAPASS設有票券轉讓功能，可將票券轉給同為使用MOBAPASS之裝置用戶。</p>

														<p class="fw-500"> <span style="font-size:1.3em; line-height: 1.3em; color: Tomato;"><i
																	class="fas fa-info-circle"></i></span>
															使用MOBAPASS前須先完成註冊 / 請選擇下方QR Code下載 <i class="fas fa-qrcode"></i>
														</p>
														<ul class="qrcode ">
															<li>
																<div class="qrimg">
																	<img src="dist/img/apple-qr-code.png" alt="IMG">
																	<span class="qr-txt"> IOS</span>
																</div>
															</li>
															<li>
																<div class="qrimg">
																	<img src="dist/img/android-qr-code.png" alt="IMG">
																	<span class="qr-txt"> Android</span>
																</div>
															</li>
														</ul>

													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-6-heading-5">
												<h3 class="box-title"><b>Q5</b>MOBAPASS還有甚麼功能嗎？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p> MOBAPASS是多功能電子票券管理APP。</p>
														<p>除了可以儲存在Gettii
															Lite的專屬購票網站取得的電子票券外，若消費者購買多張票券，也可透過APP內的功能將票券轉出給同樣有下載MOBAPASS的使用者，降低偽造紙張票券流通的風險。
														</p>
														<p>同時主辦單位也可將活動特別視頻或是照片上傳至MOBAPASS APP上，提供給購票者特別小驚喜。</p>

														<p class="fw-500"> <span style="font-size:1.3em; line-height: 1.3em; color: Tomato;"><i
																	class="fas fa-info-circle"></i></span>
															使用MOBAPASS前須先完成註冊 / 請選擇下方QR Code下載 <i class="fas fa-qrcode"></i>
														</p>
														<ul class="qrcode">
															<li>
																<div class="qrimg">
																	<img src="dist/img/apple-qr-code.png" alt="IMG">
																	<span class="qr-txt"> IOS</span>
																</div>
															</li>
															<li>
																<div class="qrimg">
																	<img src="dist/img/android-qr-code.png" alt="IMG">
																	<span class="qr-txt"> Android</span>
																</div>
															</li>
														</ul>
													</div>

													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
									</div>
								</div>
								<!-- /.tab6 -->
								<!-- tab7 -->
								<div class="tab-pane" id="tab7" role="tabpanel" aria-labelledby="tab7">
									<div class="accordion" id="accordion-tab-7">
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-7-heading-1">
												<h3 class="box-title"><b>Q1</b>忘記帳號密碼怎麼辦 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>請依照系統指示操作，若還是無法找回密碼，請致電給我們886-2-2627-1939或是Email至客服信箱info@linkst-tw.com，將會由專員主動與您聯繫。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse" id="accordion-tab-7-heading-2">
												<h3 class="box-title"><b>Q2</b>若是忘記子帳號的帳號密碼該怎麼辦？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>主帳號可從系統操作重新產生子帳號密碼。

														</p>

													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->

									</div>
								</div>
								<!-- /.tab7 -->
								<!-- tab8 -->
								<div class="tab-pane" id="tab8" role="tabpanel" aria-labelledby="tab8">
									<div class="accordion" id="accordion-tab-8">
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-8-heading-1">
												<h3 class="box-title"><b>Q1</b> 你們可以客製化售票系統嗎 ?</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<p>歡迎您致電給我們886-2-2627-1939或是Email至客服信箱info@linkst-tw.com，將會由專員主動與您聯繫。
														</p>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->
										<!-- box -->
										<div class="box box-solid collapsed-box no-radius">
											<div class="box-header with-border box-border-only" data-widget="collapse"
												id="accordion-tab-8-heading-2">
												<h3 class="box-title"><b>Q2</b>「文件下載」在哪裏？</h3>
												<!-- 收合 開關 -->
												<div class="box-tools">
													<button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
													</button>
												</div>
												<!-- /.收合 開關 -->
											</div>
											<!-- /.box-header -->
											<!-- box-body -->
											<div class="box-body">
												<!-- row -->
												<div class="row">
													<!-- col -->
													<div class="col-md-12">
														<p><b>A.</b></p>
														<ol>
															<li><a href="#" target="_blank"> Lite 服務申請表(法人) </a></li>
															<li><a href="#" target="_blank">Gettii Lite 服務申請表(個人・團體)</a></li>
														</ol>
													</div>
													<!-- /.col -->
												</div>
												<!-- /.row -->
											</div>
											<!-- /.box-body -->
											<!-- /.box-footer -->
										</div>
										<!-- /.box -->

									</div>
								</div>
								<!-- /.tab8 -->
							</div>
						</div>
					</div>
				</div>
				<!-- /.content-->

			</div>
			<!-- /.login-wrap -->
		</div>
		<!-- /.login-container -->
	</section>
	<!-- /.login-section -->
	<!-- =============================================== -->
	<!-- jQuery 3 -->
	<script src="vendor/adminlte/vendor/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Font Awesome -->
	<script defer src="css/font-awesome/js/all.js"></script>
	<!-- AdminLTE App -->
	<script src="vendor/adminlte/dist/js/adminlte.min.js"></script>


</body>

</html>