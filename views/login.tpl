{include file='partials/header.tpl'}
<div class="wrapper">
    <form class="form-signin">
        <h2 class="form-signin-heading">Please login</h2>
        <input type="text" class="form-control" name="username" placeholder="Username" required/>
        <input type="password" class="form-control" name="password" placeholder="Password" required/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        {if $error}
            <div class="alert alert-danger" role="alert">Gil</div>
        {/if}
    </form>
</div>
{include file='partials/footer.tpl'}
