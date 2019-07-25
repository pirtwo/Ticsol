<script>
export default {
  name: "BaseGrid",

  props: ["value"],

  data() {
    return {
      selects: [],
      copiedRow: {}
    };
  },

  methods: {
    copy(row) {
      this.copiedRow = Object.assign({}, row);
      return this.copiedRow;
    },

    past() {
      return this.copiedRow;
    },

    insert(row) {
      let grid = this.value.slice();
      grid.push(row);
      this.$emit("input", grid);
      this.$emit("inserted", row);
    },

    update(data, index) {
      let grid = this.value.slice();
      grid[index] = data;
      this.$emit("input", grid);
      this.$emit("updated", data);
    },

    delete(row) {
      this.$emit("input", this.value.filter(item => item !== row));
      this.$emit("deleted", row);
    },

    isSelected(row) {
      return this.selects.indexOf(row) > -1;
    }
  },

  render() {
    return this.$scopedSlots.default({
      copyRow: this.copy,
      pastRow: this.past,
      selectRow: this.select,
      insertRow: this.insert,
      updateRow: this.update,
      deleteRow: this.delete
    });
  }
};
</script>
