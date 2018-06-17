<template>
   <nav-view v-bind:scrollbar="true" v-bind:loading="this.loading" menu-title="Jobs" drawer-title="">

        <template slot="menu"></template>

        <template slot="drawer"></template>

        <template slot="content">
            
            <md-field>
                <label>Name</label>
                <md-input v-model="name"></md-input>
            </md-field>

             <md-field>
                <label>Code</label>
                <md-input v-model="code"></md-input>
            </md-field>
            
            <md-autocomplete v-model="selectedParent" :md-options="jobs" md-dense>
                <label>Parent</label>

                <template slot="md-autocomplete-item" slot-scope="{ item, term }">
                    <md-highlight-text :md-term="term">{{ item }}</md-highlight-text>
                </template>

                <template slot="md-autocomplete-empty" slot-scope="{ term }">
                    No countries matching "{{ term }}" were found. <a @click="noop()">Create a new</a> one!
                </template>
            </md-autocomplete>

            <md-autocomplete v-model="selectedProfile" :md-options="profiles" md-dense>
                <label>Profile</label>

                <template slot="md-autocomplete-item" slot-scope="{ item, term }">
                    <md-highlight-text :md-term="term">{{ item }}</md-highlight-text>
                </template>

                <template slot="md-autocomplete-empty" slot-scope="{ term }">
                    No countries matching "{{ term }}" were found. <a @click="noop()">Create a new</a> one!
                </template>
            </md-autocomplete>

            <md-switch v-model="active">Activate</md-switch>

        </template>
    </nav-view>
</template>

<script>
import NavView from "../../../framework/NavView.vue";
export default {
  name: "JobList",
  components: {
    "nav-view": NavView
  },
  data() {
    return {
        jobs: [
            'Frontend developer',
            'Backend developer',
            'Finding the Requirements',
            'Database adminstrator',
            'Tester',
            'UI Tester',
        ],
        profiles:[
            'Normal job profile',
            'Advance job profile',
            'Full property',
        ],
        name: '',
        code: '',
        active: true,
        selectedParent: null,
        selectedProfile: null,
        loading: false,
    };
  },
  mounted() {},
  methods: {}
};
</script>

<style scoped>

</style>