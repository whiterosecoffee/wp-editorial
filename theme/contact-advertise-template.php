<?php /* Template Name: Advertise Contact Form */ ?>
<?php   
    get_header();
    do_action( 'navbar' );
?>
<div class="contactWrapper">
<h2><?php echo get_the_title(); ?></h2>
<h4>تواصل معنا للإستفسار عن فرص الإعلان مع كسرة</h4>
<div id="contact_form_div">
    <form action="<?php echo home_url( '/' ); ?>" method="post">
        <div class="formDiv field name">
            <input type="text" class="form-control" id="contact-name" name="contact-name" placeholder="اسمك">
        </div>
        <div class="formDiv field name">
            <input type="text" class="form-control" id="job-title" name="job-title" placeholder="الوظيفة">
        </div>
        <div class="formDiv field name">
            <input type="email" class="form-control" id="contact-email" name="contact-email" placeholder="عنوان بريدك الالكتروني">
        </div>
        <div class="formDiv field name">
            <input type="text" class="form-control" id="company" name="company" placeholder="الشركة">
        </div>
        <div class="formDiv">
            <select class="countrySelect" name="الدولة">
                <option class="countryOption" value="الجزائر">الجزائر</option>
                <option class="countryOption" value="البحرين‎">البحرين‎</option>
                <option class="countryOption" value="جزر القمر">جزر القمر</option>
                <option class="countryOption" value="جيبوتي">جيبوتي</option>
                <option class="countryOption" value="مصر‎">مصر‎</option>
                <option class="countryOption" value="العراق‎">العراق‎</option>
                <option class="countryOption" value="لأردن">لأردن</option>
                <option class="countryOption" value="الكويت">الكويت</option>
                <option class="countryOption" value="لبنان">لبنان</option>
                <option class="countryOption" value="موريتانيا">موريتانيا</option>
                <option class="countryOption" value="المغرب">المغرب</option>
                <option class="countryOption" value="عمان">عمان</option>
                <option class="countryOption" value="فلسطين‎">فلسطين‎</option>
                <option class="countryOption" value="قطر‎">قطر‎</option>
                <option class="countryOption" value="السعودية">السعودية</option>
                <option class="countryOption" value="الصومال" selected>الصومال</option>
                <option class="countryOption" value="السودان">السودان</option>
                <option class="countryOption" value="سوريا‎">سوريا‎</option>
                <option class="countryOption" value="تونس‎">تونس‎</option>
                <option class="countryOption" value="الإمارات العربيّة المتّحدة">الإمارات العربيّة المتّحدة</option>
                <option class="countryOption" value="اليمن‎">اليمن‎</option>
            </select>
            <b class="caret"></b>

        </div>
        <div class="formDiv field name">
            <input type="text" class="form-control" id="contact-what-page" name="contact-subject" placeholder="رابط الصفحة المتعلقة بالرسالة">
        </div>
        <div class="formDiv">
            <textarea id="contact-form-text" name="contact-form-text" class="form-control" rows="5" placeholder="نص الرسالة"  maxlength="1000"></textarea> 
            <span class="char-count" class="pull-left"></span>
        </div>
        
        <div class="form-inline">
            <div class="checkbox">
                <label>
                    <input id="contact-form-newsletter-checkbox" type="checkbox" checked="checked"> أضفني إلى نشرة كسرة
                </label>
                <input type="hidden" id="mc_submit_type" name="mc_submit_type" value="js" />
                <input type="hidden" name="mcsf_action" value="mc_submit_signup_form" />
                <input type="hidden" name="mc_signup_submit" value="Subscribe" />
                <?php wp_nonce_field('mc_submit_signup_form', '_mc_submit_signup_form_nonce', false); ?>
             </div>
             <button class="resetBtn" type="reset">إلغاء</button>
             <button type="submit" class="btn btn-submit">أرسل</button>
        </div>
    </form>
</div> <!-- contact_form_div -->
</div> <!-- contactWrapper -->
<?php include('mp_home_footer.php'); ?>