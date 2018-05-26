<template>
    <nav-view>
        <template slot="pane">

        </template>
        <template slot="content">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Bill</td>
                    <td>Gates</td>
                    <td>@billy</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Steve</td>
                    <td>Jobs</td>
                    <td>@stevy</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Larry</td>
                    <td>Page</td>
                    <td>@larry</td>
                </tr>
                </tbody>
            </table>
        </template>
    </nav-view>
</template>
<script>
import NavView from '../../../framework/NavView.vue';
export default {
    name: 'JobList',
    components:{
        'nav-view': NavView,
    }
}
</script>
<style scoped>

</style>
