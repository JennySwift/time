<template>
    <div>
        <button
            v-if="redirectTo"
            v-link="{path: redirectTo}"
            class="btn btn-default"
        >
            Cancel
        </button>

        <button
            v-if="destroy"
            v-on:click="destroy()"
            v-link="{path: redirectTo}"
            class="btn btn-danger"
        >
            Delete
        </button>

        <!--Not adding the redirectTo here because that's done with the JS in case there is an error and we don't want to redirect-->
        <button
            v-if="save"
            v-on:click="save()"
            class="btn btn-success"
        >
            Save
        </button>
    </div>
</template>

<script>
    module.exports = {
        props: [
            'save',
            'destroy',
            'redirectTo'
        ]
    };
</script>