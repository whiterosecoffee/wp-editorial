<?php /* Template Name: Contact Us */ ?>
<?php
	get_header();
	do_action( 'navbar' );
?>
<section class="contactUs">
<h1>اتصل بنا</h1>

<ul id="contactUsList" class="floatfix">
    <li class="threeTwoOne">
        <a href="<?php echo site_url(); ?>/advertise" class="blockListItem">
            <h1>فرص الإعلان معنا</h1> <!-- advertise with us -->
            <p>تواصل معنا للإستفسار عن فرص الإعلان مع كسرة</p>
        </a>
    </li>
    <li class="threeTwoOne">
        <a href="<?php echo site_url(); ?>/inquiries" class="blockListItem">
            <h1>استفسارات عامة عن الموقع</h1> <!-- community help -->
            <p>تواصل مع فريق جمهور كسرة لمناقشة امور عامة عن كسرة</p>
        </a>
    </li>
    <li class="threeTwoOne">
        <a href="<?php echo site_url(); ?>/technical-support" class="blockListItem">
            <h1>مشاكل فنية</h1> <!-- technical help -->
            <p>تواصل معنا للإستفسار عن مشاكل فنية تواجهها في كسرة</p>
        </a>
    </li>
    <li class="threeTwoOne">
        <a href="<?php echo site_url(); ?>/copyright" class="blockListItem">
            <h1>حقوق النشر</h1> <!-- copyright -->
            <p>تواصل معنا إذا كنت تملك حقوق أي محتوى منشور على كسرة وتعتقد انه تم استخدامه عن طريق الخطأ</p>
        </a>
    </li>
</ul>
</section>
<?php
	include('mp_home_footer.php');
	get_footer();
?>
