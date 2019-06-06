<template>
  <app-main
    :scrollbar="false"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
        <li class="menu-title">
          Links
        </li>
      </ul>
    </template>
    <template slot="content">
      <form>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 col-form-lable">Theme</label>
            <div class="col-sm-10">
              <ts-select
                v-model="settings.theme"
                :data="themes"
                @input="setTheme"
                id="parent_id"
                name="jobParent"
                placeholder="select parent..."
                search-placeholder="search..."
              />
            </div>
          </div>
        </div>
      </form>
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";

export default {
  name: "SettingsModify",

  mixins: [pageMixin],

  data() {
    return {
      themes: [
        { key: 0, value: "Default" },
        { key: 1, value: "Urban" },
        { key: 2, value: "Jungle" },
        { key: 3, value: "Beach" },
        { key: 4, value: "Night" }
      ],
      settings: {
        theme: {}
      }
    };
  },

  computed: {
    ...mapGetters({
      getTheme: "core/getTheme"
    }),

    currentTheme: function() {     
      return this.themes.find(item => item.value.toLowerCase() === this.getTheme);
    }
  },

  mounted() {
    this.settings.theme = this.currentTheme;
  },

  methods: {
    ...mapActions({
      theme: "core/theme"
    }),

    setTheme() {
      this.theme(this.settings.theme.value);
    }
  }
};
</script>

<style>
</style>
