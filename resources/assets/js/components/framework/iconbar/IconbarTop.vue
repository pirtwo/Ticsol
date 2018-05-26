<template>
    <div class="toolbar-top">
        <div class="dropdown-button place-right">
            <button class="button shortcut">                
                <span class="caption">User</span>
                <span class="mif-user mif-2x"></span>
            </button>
            <ul class="d-menu place-right" data-role="dropdown">
                <li><a href="#">Profile</a></li>
                <li><a href="#">Change Password</a></li>                
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
        <div class="dropdown-button place-right">
            <button class="button shortcut">                
                <span class="caption">Settings</span>
                <span class="tag">3000</span>
                <span class="mif-cog mif-2x"></span>
            </button>
            <ul class="d-menu place-right" data-role="dropdown">
                <li><a href="#">Reply</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
  name: "IconbarTop"
};
</script>
<style scoped>
</style>