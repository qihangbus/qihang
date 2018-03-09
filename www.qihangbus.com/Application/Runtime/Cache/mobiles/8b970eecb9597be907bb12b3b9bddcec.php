<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport"
		  content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta content="telephone=no" name="format-detection"/>
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>启航巴士幼儿亲子读书计划</title>
	<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/base.css"/>
	<link rel="stylesheet" type="text/css" href="/Public/css/mobiles/app.css"/>
</head>
<body class="mbg" style="overflow:auto;">
<div class="wrap2 ">
	<div class="page_login">
		<div class="login_t" style="padding: 45px 0 25px;"><img src="/Public/images/mobiles/login_logo.png" alt=""/></div>
		<form name="myform" id="myform" action="<?php echo U('mobile.php/Parentlogin/validate_code');?>" method="post"
			  onsubmit="return check(this);">
			<input type="hidden" name="fack_id" value="<?php echo ($fack_id); ?>"/>
			<ul class="ul_loginform">
				<li class="li_tel">
					<div class="d_qu">+86</div>
					<input type="tel" id="mobile" name="mobile" placeholder="请输入手机号码" maxlength="11" class="l_input "/>
				</li>
				<li class="li_tel" style="padding-left:10px;margin-bottom:5px;">
					<input type="password" id="pwd" name="password" placeholder="请输入密码" maxlength="11" class="l_input"/>
				</li>
				<div style="text-align:left;line-height:30px;">
					<p>
						<input type="checkbox" style="margin-top:10px;float:left;margin-right:5px;" checked="checked"
							   name="chkbox" id="chkbox" value="1">
						<font id="test" style="color:#999;display:block;padding-top:5px;font-size:90%;">我已仔细阅读并接受<label
								style="color:#ffae00">《用户服务协议》</label></font>
					</p>
				</div>
			</ul>
			<ul class="ul_loginbtn">
				<li>
					<a href="<?php echo U('mobile.php/Parentregister/getLocation');?>" class="btn" style="background-color: #99d656;width: 48%;float:left;">注册</a>
					<a href="javascript:void(0);" class="btn btn_login btn_a" style="width:48%;float:right;">登录</a>
				</li>
			</ul>
			<div style="text-align: right;margin-top: 40px;">忘记密码</div>
			<div style="text-align: center;width: 100%;padding-bottom: 10px;">
				<div class="login_b" style="padding-top:24px;">
					启航巴士幼儿亲子读书计划
				</div>
				<a href="http://www.miitbeian.gov.cn/" target="_blank" style="color:#999;">豫ICP备16037139号-2</a>
			</div>
		</form>
	</div>

</div>

<div id="agreement" style="position:relative;display:none;z-index:999999;background-color:#ffffff;margin:15px;">
	<h1 align="center">启航巴士用户服务协议</h1>
	<p style="line-height:25px;margin-bottom:15px;">
		特别提示<br/>郑州炫世信息技术有限公司（以下简称“郑州炫世”）在此特别提醒您（用户）在激活成为用户之前，请认真阅读本《服务协议》（以下简称“协议”），确保您充分理解本协议中各条款。请您审慎阅读并选择接受或不接受本协议。除非您接受本协议所有条款，否则您无权激活、登录或使用本协议所涉服务。您的登录、使用等行为将视为对本协议的接受，并同意接受本协议各项条款的约束。<br/>本协议约定郑州炫世与用户之间关于“启航巴士”软件服务（以下简称“服务”）的权利义务。“用户”是指激活、登录、使用本服务的个人。本协议可由郑州炫世随时更新，更新后的协议条款一旦公布即代替原来的协议条款，恕不再另行通知，用户可在本应用内查阅最新版协议条款。在郑州炫世修改协议条款后，如果用户不接受修改后的条款，请立即停止使用郑州炫世提供的服务，用户继续使用郑州炫世提供的服务将被视为接受修改后的协议。<br/>一、账号激活<br/>1、用户在使用本服务前需要激活一个“启航巴士”账号。“启航巴士”账号应当使用手机号码绑定激活，请用户使用尚未与“启航巴士”账号绑定的手机号码，以及未被郑州炫世根据本协议封禁的手机号码激活“启航巴士”账号。郑州炫世可以根据用户需求或产品需要对账号激活和绑定的方式进行变更，而无须事先通知用户。<br/>2、鉴于“启航巴士”账号的绑定激活方式，您同意郑州炫世在激活时将自动提取您的手机号码及手机设备识别码等信息用于激活。<br/>3、在用户激活及使用本服务时，郑州炫世需要搜集能识别用户身份的个人信息以便郑州炫世可以在必要时联系用户，或为用户提供更好的使用体验。郑州炫世搜集的信息包括但不限于用户的姓名、性别、年龄、出生日期、身份证号、地址、学校情况、公司情况、所属行业、兴趣爱好、个人说明；郑州炫世同意对这些信息的使用将受限于第三条用户个人隐私信息保护的约束。<br/>二、服务内容<br/>1、本服务的具体内容由郑州炫世根据实际情况提供，包括但不限于授权用户通过其账号进行即时通讯、发布内容、参与评论。郑州炫世可以对其提供的服务予以变更，且郑州炫世提供的服务内容可能随时变更；用户将会收到郑州炫世关于服务变更的通知。<br/>2、郑州炫世提供的服务包含免费服务与收费服务。用户可以通过付费方式购买收费服务，具体方式为：用户通过微信、网上银行、支付宝或其他“启航巴士”平台提供的付费途径支付一定数额的人民币购买，然后根据郑州炫世公布的资费标准以用户欲购买使用的收费服务，从而获得收费服务使用权限。对于收费服务，郑州炫世会在用户使用之前给予用户明确的提示，只有用户根据提示确认其同意按照前述支付方式支付费用并完成了支付行为，用户才能使用该等收费服务。支付行为的完成以银行或第三方支付平台生成“支付已完成”的确认通知为准。<br/>三、用户个人隐私信息保护<br/>1、用户在激活账号或使用本服务的过程中，可能需要填写或提交一些必要的信息，如法律法规、规章规范性文件（以下称“法律法规”）规定的需要填写的身份信息。如用户提交的信息不完整或不符合法律法规的规定，则用户可能无法使用本服务或在使用本服务的过程中受到限制。<br/>2、个人隐私信息是指涉及用户个人身份或个人隐私的信息，比如，用户真实姓名、身份证号、手机号码、手机设备识别码、IP地址、用户聊天记录。非个人隐私信息是指用户对本服务的操作状态以及使用习惯等明确且客观反映在郑州炫世服务器端的基本记录信息、个人隐私信息范围外的其它普通信息，以及用户同意公开的上述隐私信息。<br/>3、尊重用户个人隐私信息的私有性是郑州炫世的一贯制度，郑州炫世将采取技术措施和其他必要措施，确保用户个人隐私信息安全，防止在本服务中收集的用户个人隐私信息泄露、毁损或丢失。在发生前述情形或者郑州炫世发现存在发生前述情形的可能时，将及时采取补救措施。<br/>4、郑州炫世未经用户同意不向任何第三方公开、
		透露用户个人隐私信息。但以下特定情形除外：<br/>(1) 郑州炫世根据法律法规规定或有权机关的指示提供用户的个人隐私信息；<br/>(2)
		由于用户将其用户密码告知他人或与他人共享激活帐户与密码，由此导致的任何个人信息的泄漏，或其他非因郑州炫世原因导致的个人隐私信息的泄露；<br/>
		(3) 用户自行向第三方公开其个人隐私信息；<br/>
		(4) 用户与郑州炫世及合作单位之间就用户个人隐私信息的使用公开达成约定，郑州炫世因此向合作单位公开用户个人隐私信息；<br/>
		(5) 任何由于黑客攻击、电脑病毒侵入及其他不可抗力事件导致用户个人隐私信息的泄露。<br/>
		5、用户同意郑州炫世可在以下事项中使用用户的个人隐私信息：<br/>
		(1) 郑州炫世向用户及时发送重要通知，如软件更新、本协议条款的变更；<br/>
		(2) 郑州炫世内部进行审计、数据分析和研究等，以改进郑州炫世的产品、服务和与用户之间的沟通；<br/>
		(3) 依本协议约定，郑州炫世管理、审查用户信息及进行处理措施；<br/>
		(4) 适用法律法规规定的其他事项。<br/>
		除上述事项外，如未取得用户事先同意，郑州炫世不会将用户个人隐私信息使用于任何其他用途。<br/>
		6、郑州炫世重视对未成年人个人隐私信息的保护。郑州炫世将依赖用户提供的个人信息判断用户是否为未成年人。任何18岁以下的未成年人激活账号或使用本服务应事先取得家长或其法定监护人（以下简称"监护人"）的书面同意。除根据法律法规的规定及有权机关的指示披露外，郑州炫世不会使用或向任何第三方透露未成年人的聊天记录及其他个人隐私信息。除本协议约定的例外情形外，未经监护人事先同意，郑州炫世不会使用或向任何第三方透露未成年人的个人隐私信息。任何18岁以下的用户不得下载和使用郑州炫世通过启航巴士软件提供的网络游戏。<br/>
		7、为了改善郑州炫世的技术和服务，向用户提供更好的服务体验郑州炫世或可会自行收集使用或向第三方提供用户的非个人隐私信息。<br/>
		四、内容规范<br/>
		1、本条所述内容是指用户使用本服务过程中所制作、上载、复制、发布、传播的任何内容，包括但不限于账号头像、名称、用户说明等激活信息及认证资料，或文字、语音、图片、视频、图文等发送、回复或自动回复消息和相关链接页面，以及其他使用账号或本服务所产生的内容。<br/>
		2、用户不得利用“启航巴士”账号或本服务制作、上载、复制、发布、传播如下法律、法规和政策禁止的内容：<br/>
		(1) 反对宪法所确定的基本原则的；<br/>
		(2) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；<br/>
		(3) 损害国家荣誉和利益的；<br/>
		(4) 煽动民族仇恨、民族歧视，破坏民族团结的；<br/>
		(5) 破坏国家宗教政策，宣扬邪教和封建迷信的；<br/>
		(6) 散布谣言，扰乱社会秩序，破坏社会稳定的；<br/>
		(7) 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；<br/>
		(8) 侮辱或者诽谤他人，侵害他人合法权益的；<br/>
		(9) 含有法律、行政法规禁止的其他内容的信息。<br/>
		3、用户不得利用“启航巴士”账号或本服务制作、上载、复制、发布、传播如下干扰“启航巴士”正常运营，以及侵犯其他用户或第三方合法权益的内容：<br/>
		(1) 含有任何性或性暗示的；<br/>
		(2) 含有辱骂、恐吓、威胁内容的；<br/>
		(3) 含有骚扰、垃圾广告、恶意信息、诱骗信息的；<br/>
		(4) 涉及他人隐私、个人信息或资料的；<br/>
		(5) 侵害他人名誉权、肖像权、知识产权、商业秘密等合法权利的；<br/>
		(6) 含有其他干扰本服务正常运营和侵犯其他用户或第三方合法权益内容的信息。<br/>
		五、使用规则<br/>
		1、用户在本服务中或通过本服务所传送、发布的任何内容并不反映或代表，也不得被视为反映或代表郑州炫世的观点、立场或政策，郑州炫世对此不承担任何责任。<br/>
		2、用户不得利用“启航巴士”账号或本服务进行如下行为：<br/>
		(1) 提交、发布虚假信息，或盗用他人头像或资料，冒充、利用他人名义的；<br/>
		(2) 强制、诱导其他用户关注、点击链接页面或分享信息的；<br/>
		(3) 虚构事实、隐瞒真相以误导、欺骗他人的；<br/>
		(4) 利用技术手段批量建立虚假账号的；<br/>
		(5) 利用“启航巴士”账号或本服务从事任何违法犯罪活动的；<br/>
		(6) 制作、发布与以上行为相关的方法、工具，或对此类方法、工具进行运营或传播，无论这些行为是否为商业目的；<br/>
		(7) 其他违反法律法规规定、侵犯其他用户合法权益、干扰“启航巴士”正常运营或郑州炫世未明示授权的行为。<br/>
		3、用户须对利用“启航巴士”账号或本服务传送信息的真实性、合法性、无害性、准确性、有效性等全权负责，与用户所传播的信息相关的任何法律责任由用户自行承担，与郑州炫世无关。如因此给郑州炫世或第三方造成损害的，用户应当依法予以赔偿。<br/>
		4、郑州炫世提供的服务中可能包括广告，用户同意在使用过程中显示郑州炫世和第三方供应商、合作伙伴提供的广告。除法律法规明确规定外，用户应自行对依该广告信息进行的交易负责，对用户因依该广告信息进行的交易或前述广告商提供的内容而遭受的损失或损害，郑州炫世不承担任何责任。<br/>
		5、您在使用过程中不得利用启航巴士的相关软件或规则漏洞进行恶意套取积分或其他虚拟产品，启航巴士有权在不通知您本人的情况下，收回该启航巴士账号进行恶意套取的积分或其他虚拟产品，并根据情况在启航巴士内进行相应的处罚，若情节严重，启航巴士有权永久禁用其账号。<br/>
		6、凡借阅的图书，家长应爱护书籍，不得卷折、涂画、污损、撕页或遗失，并及时引导、教育孩子爱护书籍（启航巴士提供教育课件），如有发生将按本规定处理。<br/>
		●遗失的图书，家长可以自行购买同出版社同版本的图书赔偿，也可以通过“启航巴士”线上平台链接购买赔偿，付款后我司会负责后续的书本配送工作。<br/>
		●赔偿的图书可直接通过“启航巴士”线上平台链接购买，家长线上付款后，我司工作人员会与园方及时联系，由我司负责把相应图书归还园所，并家长恢复线上借阅图书权限。<br/>
		损毁图书按下列情况处理：（赔偿费用在“启航巴士”平台支付）
		●损坏严重，影响图书内容完整及使用、保存，应购原版图书赔偿。<br/>
		●污损或损毁图书封面，一般图书赔偿三元，精装图书赔偿五元。<br/>
		●污损书刊内容，但不影响阅读和保存，按一般图书赔偿三元，精装图书赔偿五元。<br/>
		●凡按原版本图书赔偿者，被损图书经赔偿后可归赔偿者所有。<br/>
		●家长遗失或污损图书未赔偿前将被暂停图书借阅。<br/>
		●家长借阅书刊资料时，应仔细检查所借书刊资料情况，如有污损，应立即向老师声明，需要赔偿的由老师把赔偿链接推送给上一次借阅的家长，否则应由本次借阅的家长负责。<br/>
		六、虚拟货币<br/>
		1、郑州炫世将在“启航巴士”平台发行虚拟货币，即金豆。金豆可用于购买“启航巴士”平台的增值服务，包括但不限于表情服务及会员服务，除此外，不得用于其他任何用途。该等增值服务的价格均以金豆为单位，具体价格信息将由郑州炫世自行决定并在相关服务页面上显示。<br/>
		2、金豆和人民币的兑换比例将由郑州炫世根据运营情况随时变更，并将在用户购买金豆的相关服务页面上显示。<br/>
		3、用户默认已开通金豆账户，可进行金豆购买和消费。用户可在设置页面查询到金豆余额、购买记录和消费记录。金豆相关信息将不作为公开信息。<br/>
		4、用户可以通过微信、网上银行、支付宝或其他“启航巴士”平台提供的充值途径为金豆账户进行充值。
		5、用户确认，金豆一经充值成功，除法律法规明确规定外，在任何情况下不能兑换为法定货币，不能转让他人。除法律法规明确规定外，金豆账户充值完成后，郑州炫世不予退款。<br/>
		6、用户确认，金豆只能用于购买“启航巴士”平台上的各类增值服务，任何情况下不得与郑州炫世以外的第三方进行金豆交易，亦不得在除“启航巴士”平台以外的第三方平台（如淘宝）上进行交易；如违反前述约定，造成用户或第三方任何损失，郑州炫世不负任何责任，且如郑州炫世有理由怀疑用户的金豆帐户或使用情况有作弊或异常状况，郑州炫世将拒绝该用户使用金豆进行支付，直至按本协议约定采取相关封禁措施。<br/>
		7、用户确认，除法律法规明确规定或本协议另有约定外，用户已购买的任何收费服务不能以任何理由退购（即退换成金豆或法定货币）或调换成其他服务。<br/>
		七、帐户管理<br/>
		1、
		“启航巴士”账号的所有权归郑州炫世所有，用户完成申请激活手续后，获得“启航巴士”账号的使用权，该使用权仅属于初始申请激活人，禁止赠与、借用、租用、转让或售卖。郑州炫世因经营需要，有权回收用户的“启航巴士”账号。<br/>
		2、用户可以更改、删除“启航巴士”帐户上的个人资料、激活信息及传送内容等，但需注意，删除有关信息的同时也会删除用户储存在系统中的文字和图片。用户需承担该风险。<br/>
		3、用户有责任妥善保管激活账号信息及账号密码的安全，因用户保管不善可能导致遭受盗号或密码失窃，责任由用户自行承担。用户需要对激活账号以及密码下的行为承担法律责任。用户同意在任何情况下不使用其他用户的账号或密码。在用户怀疑他人使用其账号或密码时，用户同意立即通知郑州炫世。<br/>
		4、用户应遵守本协议的各项条款，正确、适当地使用本服务，如因用户违反本协议中的任何条款，郑州炫世在通知用户后有权依据协议中断或终止对违约用户“启航巴士”账号提供服务。同时，郑州炫世保留在任何时候收回“启航巴士”账号、用户名的权利。<br/>
		5、如用户激活“启航巴士”账号后一年不登录，通知用户后，郑州炫世可以收回该账号，以免造成资源浪费，由此造成的不利后果由用户自行承担。<br/>
		八、数据储存<br/>
		1、郑州炫世不对用户在本服务中相关数据的删除或储存失败负责。<br/>
		2、郑州炫世可以根据实际情况自行决定用户在本服务中数据的最长储存期限，并在服务器上为其分配数据最大存储空间等。用户可根据自己的需要自行备份本服务中的相关数据。<br/>
		3、如用户停止使用本服务或本服务终止，郑州炫世可以从服务器上永久地删除用户的数据。本服务停止、终止后，郑州炫世没有义务向用户返还任何数据。<br/>
		九、风险承担<br/>
		1、用户理解并同意，“启航巴士”仅为用户提供信息分享、传送及获取的平台，用户必须为自己激活账号下的一切行为负责，包括用户所传送的任何内容以及由此产生的任何后果。用户应对“启航巴士”及本服务中的内容自行加以判断，并承担因使用内容而引起的所有风险，包括因对内容的正确性、完整性或实用性的依赖而产生的风险。郑州炫世无法且不会对因用户行为而导致的任何损失或损害承担责任。
		如果用户发现任何人违反本协议约定或以其他不当的方式使用本服务，请立即向郑州炫世举报或投诉，郑州炫世将依本协议约定进行处理。<br/>
		2、用户理解并同意，因业务发展需要，郑州炫世保留单方面对本服务的全部或部分服务内容变更、暂停、终止或撤销的权利，用户需承担此风险。<br/>
		十、知识产权声明<br/>
		1、除本服务中涉及广告的知识产权由相应广告商享有外，郑州炫世在本服务中提供的内容（包括但不限于网页、文字、图片、音频、视频、图表等）的知识产权均归郑州炫世所有，但用户在使用本服务前对自己发布的内容已合法取得知识产权的除外。<br/>
		2、除另有特别声明外，郑州炫世提供本服务时所依托软件的著作权、专利权及其他知识产权均归郑州炫世所有。<br/>
		3、郑州炫世在本服务中所涉及的图形、文字或其组成，以及其他郑州炫世标志及产品、服务名称（以下统称“郑州炫世标识”），其著作权或商标权归郑州炫世所有。未经郑州炫世事先书面同意，用户不得将郑州炫世标识以任何方式展示或使用或作其他处理，也不得向他人表明用户有权展示、使用、或其他有权处理郑州炫世标识的行为。<br/>
		4、上述及其他任何郑州炫世或相关广告商依法拥有的知识产权均受到法律保护，未经郑州炫世或相关广告商书面许可，用户不得以任何形式进行使用或创造相关衍生作品。<br/>
		十一、法律责任<br/>
		1、如果郑州炫世发现或收到他人举报或投诉用户违反本协议约定的，郑州炫世有权不经通知随时对相关内容，包括但不限于用户资料、聊天记录进行审查、删除，并视情节轻重对违规账号处以包括但不限于警告、账号封禁 、设备封禁 、功能封禁
		的处罚，且通知用户处理结果。<br/>
		2、因违反服务协议被封禁的用户，可向郑州炫世客服电话：037155053766提交申诉，郑州炫世将对申诉进行审查，并自行合理判断决定是否变更处罚措施。<br/>
		3、用户理解并同意，郑州炫世有权依合理判断对违反有关法律法规或本协议规定的行为进行处罚，对违法违规的任何用户采取适当的法律行动，并依据法律法规保存有关信息向有关部门报告等，用户应承担由此而产生的一切法律责任。<br/>
		4、用户理解并同意，因用户违反本协议约定，导致或产生的任何第三方主张的任何索赔、要求或损失，包括合理的律师费，用户应当赔偿郑州炫世与合作公司、关联公司，并使之免受损害。<br/>
		十二、不可抗力及其他免责事由<br/>
		1、用户理解并确认，在使用本服务的过程中，可能会遇到不可抗力等风险因素，使本服务发生中断。不可抗力是指不能预见、不能克服并不能避免且对一方或双方造成重大影响的客观事件，包括但不限于自然灾害如洪水、地震、瘟疫流行和风暴等以及社会事件如战争、动乱、政府行为等。出现上述情况时，郑州炫世将努力在第一时间与相关单位配合，及时进行修复，但是由此给用户或第三方造成的损失，郑州炫世及合作单位在法律允许的范围内免责。<br/>
		2、本服务同大多数互联网服务一样，受包括但不限于用户原因、网络服务质量、社会环境等因素的差异影响，可能受到各种安全问题的侵扰，如他人利用用户的资料，造成现实生活中的骚扰；用户下载安装的其它软件或访问的其他网站中含有“特洛伊木马”等病毒，威胁到用户的计算机信息和数据的安全，继而影响本服务的正常使用等等。用户应加强信息安全及使用者资料的保护意识，要注意加强密码保护，以免遭致损失和骚扰。<br/>
		3、用户理解并确认，本服务存在因不可抗力、计算机病毒或黑客攻击、系统不稳定、用户所在位置、用户关机以及其他任何技术、互联网络、通信线路原因等造成的服务中断或不能满足用户要求的风险，因此导致的用户或第三方任何损失，郑州炫世不承担任何责任。<br/>
		4、用户理解并确认，在使用本服务过程中存在来自任何他人的包括误导性的、欺骗性的、威胁性的、诽谤性的、令人反感的或非法的信息，或侵犯他人权利的匿名或冒名的信息，以及伴随该等信息的行为，因此导致的用户或第三方的任何损失，郑州炫世不承担任何责任。<br/>
		5、用户理解并确认，郑州炫世需要定期或不定期地对“启航巴士”平台或相关的设备进行检修或者维护，如因此类情况而造成服务在合理时间内的中断，郑州炫世无需为此承担任何责任，但郑州炫世应事先进行通告。<br/>
		6、郑州炫世依据法律法规、本协议约定获得处理违法违规或违约内容的权利，该权利不构成郑州炫世的义务或承诺，郑州炫世不能保证及时发现违法违规或违约行为或进行相应处理。<br/>
		7、用户理解并确认，对于郑州炫世向用户提供的下列产品或者服务的质量缺陷及其引发的任何损失，郑州炫世无需承担任何责任：<br/>
		(1) 郑州炫世向用户免费提供的服务；<br/>
		(2)郑州炫世向用户赠送的任何产品或者服务。<br/>
		8、在任何情况下，郑州炫世均不对任何间接性、后果性、惩罚性、偶然性、特殊性或刑罚性的损害，包括因用户使用“启航巴士”或本服务而遭受的利润损失，承担责任（即使郑州炫世已被告知该等损失的可能性亦然）。尽管本协议中可能含有相悖的规定，郑州炫世对用户承担的全部责任，无论因何原因或何种行为方式，始终不超过用户因使用郑州炫世提供的服务而支付给郑州炫世的费用(如有)。<br/>
		十三、服务的变更、中断、终止<br/>
		1、鉴于网络服务的特殊性，用户同意郑州炫世有权随时变更、中断或终止部分或全部的服务（包括收费服务）。郑州炫世变更、中断或终止的服务，郑州炫世应当在变更、中断或终止之前通知用户，并应向受影响的用户提供等值的替代性的服务；如用户不愿意接受替代性的服务，如果该用户已经向郑州炫世支付收费服务费，郑州炫世应当按照该用户实际使用服务的情况扣除相应费用之后将剩余的费用退还用户的账户中。<br/>
		2、如发生下列任何一种情形，郑州炫世有权变更、中断或终止向用户提供的免费服务或收费服务，而无需对用户或任何第三方承担任何责任：<br/>
		(1) 根据法律规定用户应提交真实信息，而用户提供的个人资料不真实、或与激活时信息不一致又未能提供合理证明；<br/>
		(2) 用户违反相关法律法规或本协议的约定；<br/>
		(3) 按照法律规定或有权机关的要求；<br/>
		(4) 出于安全的原因或其他必要的情形。<br/>
		十四、其他<br/>
		1、郑州炫世郑重提醒用户注意本协议中免除郑州炫世责任和限制用户权利的条款，请用户仔细阅读，自主考虑风险。未成年人应在法定监护人的陪同下阅读本协议。<br/>
		2、本协议的效力、解释及纠纷的解决，适用于中华人民共和国法律。若用户和郑州炫世之间发生任何纠纷或争议，首先应友好协商解决，协商不成的，用户同意将纠纷或争议提交郑州炫世住所地有管辖权的人民法院管辖。<br/>
		3、本协议的任何条款无论因何种原因无效或不具可执行性，其余条款仍有效，对双方具有约束力。</p>
	<a href="javascript:void(0);" class="btn btn_login btn_agreement" style="">关闭</a>
</div>

<!--page2-->
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script type="text/javascript">
	var InterValObj;
	var count = 60;
	var curCount;


	//用户协议
	$('#test').on('click', function () {
		$("#agreement").toggle();
	});

	$(".btn_agreement").on("click", function () {
		$("#agreement").toggle();
	});


	function check() {
		var mobile = $("#mobile").val();
		if (mobile == '') {
			layer.msg('请输入手机号',{time:2000});
			return false;
		}
		if($('#pwd').val() == ''){
			layer.msg('请输入密码',{time:2000});
			return false;
		}
		if (!$("#chkbox").is(':checked')) {
			layer.msg('请您先阅读用户协议',{time:2000});
			return false;
		}
		return true;
	}

	$(function () {
		$(".btn_a").click(function () {
			$("#myform").submit();
		});
	})
</script>

</body>
</html>