<template>
    <div class="checkbox-container">
        <input
            v-model="model"
            :id="id"
            type="checkbox"
        >
        <label :for="id">{{label}}</label>
    </div>
</template>

<script>
    module.exports = {
        props: [
            'label',
            'model',
            'id'
        ]
    };
</script>