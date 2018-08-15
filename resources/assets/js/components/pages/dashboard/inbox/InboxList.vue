<template>
    <nav-view 
    :scrollbar="true" 
    :loading="false"
    menu-title="Inbox" 
    drawer-title="Actions"
    padding="p-5">        
        <template slot="menu">

        </template>
        <template slot="drawer">

        </template>
        <template slot="content">
            <select-box 
            v-model="value1" 
            :data="options" 
            :multi-select="false" 
            :enable-searchbox="true" 
            :default="value3"            
            search-placeholder="search..." 
            output-type="key"
            name="selectJob"></select-box>

            <br>
            <br>

            <auto-complete v-model="value2" :data="options" name="jobs"></auto-complete>

            <br><br>
            <!-- <date-picker v-model="value3" range="Week"></date-picker> -->

            <br><br>

            <form-gen :schema="schema" v-model="form"></form-gen>

            <button type="button" @click="clickHandler">Submit</button>
            
        </template>
    </nav-view>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import AutoComplete from "../../../framework/BaseAutoComplete.vue";
import SelectBox from "../../../framework/BaseSelectBox.vue";
import DatePicker from "../../../framework/BaseDatePicker.vue";
import FormGen from "../../../framework/BaseFormGenerator/BaseFormGenerator.vue";

export default {
  name: "InboxList",

  components: {
    "nav-view": NavView,
    "auto-complete": AutoComplete,
    "select-box": SelectBox,
    "date-picker": DatePicker,
    "form-gen": FormGen
  },

  data() {
    return {
      form: {},
      schema: [
        {
          type: "text",
          required: true,
          label: "User Name",
          className: "form-control",
          placeholder: "select country...",
          name: "username",
          subtype: "text",
          maxlength: "150"
        },
        {
          type: "date",
          required: true,
          label: "Birthdate",
          description: "help",
          placeholder: "text...",
          className: "form-control",
          name: "birthday"
        },
        {
          type: "textarea",
          label: "Report",
          placeholder: "select country...",
          className: "form-control",
          name: "report",
          subtype: "textarea",
          maxlength: "150",
          rows: "10"
        },
        {
          type: "select",
          label: "Select Country",
          placeholder: "select country...",
          className: "form-control",
          name: "country",
          multiple: true,
          values: [
            {
              label: "USA",
              value: "USA",
              selected: true
            },
            {
              label: "France",
              value: "France",
              selected: true
            },
            {
              label: "Iran",
              value: "Iran"
            }
          ]
        },
        {
          type: "radio-group",
          required: true,
          label: "Sex",
          inline: true,
          name: "sex",
          values: [
            {
              label: "Male",
              value: "Male",
              selected: true
            },
            {
              label: "Female",
              value: "Female"
            },
            {
              label: "Trans",
              value: "Trans"
            }
          ]
        },
        {
          type: "checkbox-group",
          required: true,
          label: "Order Type",
          description: "help",
          toggle: true,
          inline: true,
          name: "type",
          values: [
            {
              label: "Free",
              value: "Free"
            },
            {
              label: "Gift",
              value: "Gift"
            }
          ]
        }
      ],
      value1: {},
      value2: {},
      value3: 0
    };
  },

  mounted() {
    this.list({ resource: "job" })
      .then(() => {
        this.value3 = 3;
      })
      .catch(error => {
        console.log(error);
      });
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    options: function() {
      return this.getList("job").map(item => {
        return { key: item.id, value: item.title };
      });
    }
  },

  watch: {
    value1: function(val) {
      console.log(val);
    },
    value2: function(val) {
      console.log(val);
    },
    value3: function(val) {
      console.log(val);
    },
    form: function(val) {
      console.log(val);
    }
  },

  methods: {
    ...mapActions({
      list: "resource/list"
    }),

    clickHandler() {
      console.log(this.form.username);
      console.log(this.form.birthday);
      console.log(this.form.report);
      console.log(this.form.country);
      console.log(this.form.sex);
      console.log(this.form.type);
    }
  }
};
</script>
<style scoped>
</style>
