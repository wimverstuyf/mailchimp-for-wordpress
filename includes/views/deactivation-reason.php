<?php defined( 'ABSPATH' ) or exit; ?>

<style type="text/css">

    label {
        display: block;
        font-weight: bold;
    }
    input[type="text"],
    textarea,
    select, option {
        font-size: 14px;
        padding: 6px;
        min-width: 320px;
        -webkit-appearance: none;
    }

    select {
        height: 30px;
        background: #fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='#333' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right .75rem center;
        background-size: 8px 10px;
    }
    textarea{
        height: 200px;
    }
</style>

<h3>What made you deactivate MailChimp for WordPress?</h3>
<p>Please take a minute to explain why MailChimp for WordPress is no longer right for you.</p>

<form method="POST" action="https://mc4wp.com/deactivation-reason.php">
    <p>
        <label for="reason">Reason</label>
        <select name="reason" id="reason" class="widefat">
            <option>It does not work</option>
            <option>It is too complicated</option>
            <option>It is missing an important feature</option>
            <option>Something else..</option>
        </select>
    </p>

    <p>
        <label for="explanation">Please explain</label>
        <textarea id="explanation" name="explanation" class="widefat" placeholder="Please tell us a little more.."></textarea>
    </p>

    <p>
        <input type="submit" class="button button-primary" value="Submit" /> &nbsp; <a href="<?php echo admin_url('plugins.php'); ?>">Or skip this form</a>
    </p>
</form>
