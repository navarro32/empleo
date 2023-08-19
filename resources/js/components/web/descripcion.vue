<template>
    <div>
        <textarea name="editor1" id="editor12" cols="30" rows="10"></textarea>
    </div>
</template>

<script>
require('ckeditor4');

export default {
    mounted () {
        CKEDITOR.replace( 'editor1'  );
    },
}
</script>

<style lang="scss" scoped>

</style>