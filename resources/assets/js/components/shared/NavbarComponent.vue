<template>
    <ul id="navbar" style="z-index:1000">

        <li class="big-screens"><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-clock-o"></span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a v-link="{path: '/timers'}" href="#">Timers</a></li>
                <li><a v-link="{path: '/activities'}" href="#">Activities</a></li>
                <li><a v-link="{path: '/activities/#/add'}" href="#">New Activity</a></li>
            </ul>
        </li>

        <li>
            <a v-on:click="showFilters()" href="#" class="fa fa-search"></a>
        </li>

        <!--User-->
        <li id="menu-dropdown" class="dropdown gravatar-li">
            <a href="#" data-toggle="dropdown">
                You
            </a>
            <a href="#" data-toggle="dropdown" class="gravatar-container">
                <img v-bind:img="shared.me.gravatar" class="gravatar"/>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <!-- if they are a guest they shouldn't see this page anyway, but so that my code will work... -->
                <li><a href="/logout">Logout</a></li>
            </ul>
        </li>

    </ul>
</template>

<script>
    require('bootstrap');

    module.exports = {
        template: '#navbar-template',
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        methods: {
            /**
             *
             */
            showFilters: function () {
                store.set(true, 'showFilters')
            }
        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };

</script>

