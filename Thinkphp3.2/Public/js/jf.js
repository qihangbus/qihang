$(document).ready(function () {
	//�޸��ʼĵ�ַ����-��ѡ��
	$('.popup1-p1').click(function () {
		$(this).toggleClass('popup1-p1-click');
	});

	//�޸��ʼĵ�ַ����-�޸ĵ��
	$('.popup1-a1').click(function () {
		$(this).parent().parent().parent().parent().css('display', 'none');
		$('.popup1').eq(2).css('display', '');
	});

	//�޸��ʼĵ�ַ����-����ʼĵ�ַ
	$('.popup1-li2').click(function () {
		$(this).parent().parent().css('display', 'none');
		$('.popup1').eq(2).css('display', '');
	});

	//�޸��ʼĵ�ַ����-ȷ�ϰ�ť
	//$('.popup1-a2').click(function () {
		//$(this).parent().parent().parent().css('display', 'none');
		//$('.popup1').eq(3).css('display','');
	//});


	//�һ����鵯��-�޸İ�ť
	$('.popup2-div1-a1').click(function () {
		$(this).parent().parent().parent().css('display', 'none');
		$('.popup1').eq(2).css('display', '');
	});


	//��д�ջ���ַ-��ѡ��
	$('.popup3-ul1-li2 i').click(function () {
		$(this).toggleClass('popup1-p1-click');
	});

	//indexҳ���л���ť
	$('.cs-ul3-li2 a').click(function () {
		var con = $('.cs-ul3-li2 a');
		con.attr('class', '').eq(con.index(this)).attr('class', 'cs-ul3-li2-a1');
	});


	//�����ƶ�-���ְٷֱȻ�
	var jfnum=parseInt($('.yh-li1 p').text() / $('.yh-li3').text() * 100);
	$('.yh-li1 p').css('left',jfnum+'%');
	$('.yh-li2 p').css('width',jfnum+'%');

});