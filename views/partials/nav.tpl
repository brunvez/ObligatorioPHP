<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#collapsible-links">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Real Estate</a>
        </div>

        <div class="collapse navbar-collapse" id="collapsible-links">
            <ul class="nav navbar-nav">
                <li class="{if $location === 'properties'}active{/if}"><a href="/properties">Properties</a></li>
                <li class="{if $location === 'statistics'}active{/if}"><a href="/cities/statistics">Statistics</a></li>
                <li class="{if $location === 'most_expensive'}active{/if}">
                    <a href="/properties/most_expensive">Most Expensive Properties</a>
                </li>
                {if $user_is_logged}
                    <li class="{if $location === 'admin'}active{/if}">
                        <a href="/properties/manage_properties">Manage Properties</a>
                    </li>
                    <li class="{if $location === 'unanswered_questions'}active{/if}">
                        <a href="/questions/unanswered">Unanswered Questions<span
                                    class="badge">{$unanswered_questions}</span></a>
                    </li>
                {/if}
            </ul>
            {if $user_is_logged}
                <p class="navbar-text navbar-right">Signed in as {$user->full_name()|capitalize},
                    <a href="/logout">Logout</a></p>
            {else}
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">Login <span class="caret"></span></a>
                        <ul class="dropdown-menu" id="login-form">
                            <li>
                                <div class="col-sm-12">
                                    <h3>Login</h3>
                                    <form action="/login" method="post">
                                        <div class="form-group">
                                            <label for="login-username">Username</label>
                                            <input type="text" class="form-control" name="username" id="login-username"
                                                   placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <label for="login-password">Password</label>
                                            <input type="password" class="form-control" name="password"
                                                   id="login-password"
                                                   placeholder="Password">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            {/if}
        </div>
    </div>
</nav>