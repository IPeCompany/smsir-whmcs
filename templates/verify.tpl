<h2>وریفای شماره تماس</h2>

{$smsir_vo_alert}

{if $steps == 'start'}
    <form method="post">
        <div class="form-group">
            <label>شماره تماس ثبت شده شما {$phonenumber} می باشد . برای استفاده از امکانات حساب نیاز است شماره خود را ابتدا تایید بفرمایید.</label>
        </div>
        <button type="submit" class="btn btn-primary">تایید شماره تماس</button>
    </form>
{/if}


{if $steps == 'inputcode'}
    <form method="post">
        <div class="form-group">
            <label>کد ارسال شده به تلفن شما</label>
            <input type="text" name="yourcode" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">وریفای شماره تلفن</button>
    </form>
{/if}

{if $steps == 'ban'}
    <div>فعلا مسدود شده اید</div>
{/if}

{if $steps == 'success'}
    <div class="alert alert-success">شماره شما وریفای شده است و هم اکنون به تمام امکانات وبسایت دسترسی خواهید داشت</div>
{/if}