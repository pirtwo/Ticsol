<template>
  <ul>
    <template v-if="node">
      <li>
        <slot :item="node" />
        <template v-if="hasSuccessors(node.key)">
          <TreeNode          
            v-for="successor in successors(node.key)"
            :key="successor.key"
            :node="successor"
            :data="data"
          >
            <template
              v-for="slot in Object.keys($scopedSlots)"
              :slot="slot"
              slot-scope="scope"
            >
              <slot
                :name="slot"
                v-bind="scope"
              />
            </template>
          </TreeNode>
        </template>
      </li>
    </template>
    <template v-else>
      <li
        v-for="root in roots"
        :key="root.key"
      >
        <slot :item="root" />
        <template v-if="hasSuccessors(root.key)">
          <TreeNode          
            v-for="successor in successors(root.key)"
            :key="successor.key"
            :node="successor"
            :data="data"
          >
            <template
              v-for="slot in Object.keys($scopedSlots)"
              :slot="slot"
              slot-scope="scope"
            >
              <slot
                :name="slot"
                v-bind="scope"
              />
            </template>
          </TreeNode>
        </template>
      </li>
    </template>    
  </ul>
</template>

<script>
export default {
  name: "TreeNode",

  components: {},

  props: {
    node: {
      type: Object,
      default: null
    },

    roots:{
      type: Array,
      default: () => {
        return [];
      } 
    },    

    data: {
      type: Array,
      default: () => {
        return [];
      }
    }
  },

  data() {
    return {};
  },

  computed: {

    /**
     * returns the root elements.
     */
    // treeRoots: function() {      
    //   //
    // },
  },

  methods: {

    /**
     * checks if the node has successors.
     */
    hasSuccessors(key) {
      return (
        this.data.find(item => item.parentKey === key) !== null
      );
    },

    /**
     * returns node successors.
     */
    successors(key) {
      return this.data.filter(item => item.parentKey === key);
    }
  }
};
</script>

<style scoped>
</style>
