<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="toolbar">
      <ts-pagination
        v-model="pager"
        :page-count="pager.pageCount"
        @input="changePage"
      />
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
        <li>
          <button
            class="btn"
            @click="sendModal = true"
          >
            New
          </button>
        </li>
        <li>
          <button class="btn">
            Cancel
          </button>
        </li>
        <li class="menu-title">
          Links
        </li>
      </ul>
    </template>

    <template slot="content">
      <ul class="comment-list">
        <li
          v-for="parent in comments"
          :key="parent.id"
        >
          <ts-comment
            name="VbComment"
            @reply="onReply"
            @edit="onEdit"
            @delete="onDelete"
            v-bind="parent"
            :avatar="parent.creator.meta.avatar"
            :username="parent.creator.name"            
            :created-at="getPassedTime(parent.created_at)"
            :updated-at="parent.created_at !== parent.updated_at ? getPassedTime(parent.updated_at) : ''"
            :has-edit="parent.creator.id === userId"
            :has-delete="parent.creator.id === userId"
          />
          <ul
            class="reply-list"
            v-if="parent.reply_count > 0"
          >
            <li
              v-for="child in parent.childs"
              :key="child.id"
            >
              <ts-comment
                name="VbComment"
                @edit="onEdit"
                @delete="onDelete"
                v-bind="child"
                :username="child.creator.name" 
                :avatar="child.creator.meta.avatar"
                :created-at="getPassedTime(child.created_at)"
                :updated-at="child.created_at !== child.updated_at ? getPassedTime(child.updated_at) : ''"
                :has-reply="false"
                :has-edit="child.creator.id === userId"
                :has-delete="child.creator.id === userId"
              />
            </li>
          </ul>
        </li>
      </ul>
      <!-- Send Modal -->
      <ts-modal
        :show.sync="sendModal"
        @hidden="cancelModal"
      >
        <template slot="header">
          <h5 class="modal-title">
            <span v-if="parentId">
              Reply to
              <b>{{ getCreatorName(parentId) }}</b>
            </span>
            <span v-else-if="editMode">Update Comment</span>
            <span v-else-if="!editMode">Send Comment</span>
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </template>
        <editor
          v-model="commentBody"
          :init="{height: 300, menubar: false, statusbar: false}"
          api-key="he51k5qf4qe8668k9rgkie9c13j01h43fh61m72chuvv93ip"
          plugins="bbcode code"
          toolbar="newdocument | undo redo | cut copy paste pastetext | selectall | code"
        />
        <template slot="footer">
          <button
            v-if="!editMode"
            type="button"
            class="btn btn-primary"
            @click="sendComment"
          >
            Send
          </button>
          <button
            v-if="editMode"
            type="button"
            class="btn btn-primary"
            @click="editComment"
          >
            Update
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="cancelModal"
          >
            Cancel
          </button>
        </template>
      </ts-modal>
      <!-- Confirm modal -->
      <ts-modal
        :show.sync="confirmModal"
        title="Confirm Delete"
        size="sm"
      >
        <b>Delete this comment permanently?</b>
        <template slot="footer">
          <button
            class="btn btn-outline-danger"
            @click="deleteComment"
          >
            Yes
          </button>
          <button
            class="btn btn-outline-secondary"
            @click="confirmModal = false"
          >
            No
          </button>
        </template>
      </ts-modal>
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import moment from "moment";
import PageMixin from "../../../../mixins/page-mixin.js";
import Editor from "@tinymce/tinymce-vue";

export default {
  name: "CommentList",

  mixins: [PageMixin],

  components: {
    editor: Editor
  },

  props: ["entity", "id"],

  data() {
    return {
      commentId: null,
      parentId: null,
      commentBody: "",
      sendModal: false,
      confirmModal: false,
      editMode: false,
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      }
    };
  },

  computed: {
    ...mapGetters({
      userId: "user/getId",
      getList: "resource/getList"
    }),

    comments: function() {
      return this.getList("comment");
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {});
  },

  beforeRouteLeave(to, from, next) {
    this.clear("comment");
    next();
  },

  mounted() {
    this.startLoading();
    $(document).on("focusin", function(e) {
      if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
      }
    });
    this.loadComments()
      .then(respond => {
        this.pager.pageCount = respond.last_page;
        this.stopLoading();
      })
      .catch(error => {
        this.stopLoading();
        this.showMessage(error.message, "danger");
      });
  },

  methods: {
    ...mapActions({
      clear: "resource/clearResource",
      list: "resource/list",
      create: "resource/create",
      update: "resource/update",
      delete: "resource/delete"
    }),

    changePage() {
      this.startLoading();
      this.loadComments()
        .then(respond => {
          this.pager.pageCount = respond.last_page;
          this.stopLoading();
        })
        .catch(error => {
          this.stopLoading();
          this.showMessage(error.message, "danger");
        });
    },

    loadComments() {
      return this.list({
        resource: "comment",
        query: {
          id: this.id,
          entity: this.entity,
          page: this.pager.page,
          perPage: this.pager.perPage
        }
      });
    },

    getCreatorName(commentId) {
      if (!commentId) return "";
      return this.comments.find(item => item.id === commentId).creator.name;
    },

    getPassedTime(date) {
      let utcDate = moment.utc(date).toDate();
      return moment(utcDate)
        .local()
        .fromNow();
    },

    onReply(id) {
      this.sendModal = true;
      this.parentId = id;
    },

    onEdit(e) {
      this.commentId = e.id;
      this.commentBody = e.body;
      this.editMode = true;
      this.sendModal = true;
    },

    onDelete(id) {
      this.commentId = id;
      this.confirmModal = true;
    },

    cancelModal() {
      this.commentBody = "";
      this.parentId = null;
      this.editMode = false;
      this.sendModal = false;
    },

    sendComment(e) {
      e.preventDefault();
      e.target.disabled = true;

      let comment = {
        entity: this.entity,
        parent_id: this.parentId,
        body: this.commentBody,
        [`${this.entity}_id`]: this.id
      };

      this.create({ resource: "comment", data: comment })
        .then(data => {
          this.showMessage("Comment submited successfuly.", "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          this.parentId = null;
          this.sendModal = false;
          e.target.disabled = false;
        });
    },

    editComment(e) {
      e.preventDefault();
      e.target.disabled = true;

      let data = {
        body: this.commentBody
      };

      this.update({ resource: "comment", id: this.commentId, data: data })
        .then(() => {
          this.showMessage("Comment updated successfuly.", "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          this.sendModal = false;
          e.target.disabled = false;
        });
    },

    deleteComment(e) {
      e.preventDefault();
      e.target.disabled = true;

      this.delete({ resource: "comment", id: this.commentId })
        .then(() => {
          this.showMessage("Comment deleted successfuly.", "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          this.confirmModal = false;
          e.target.disabled = false;
        });
    }
  }
};
</script>

<style scoped>
.comment-list {
  list-style: none;
  margin: 0px;
  padding: 0px;
}

.reply-list {
  list-style: none;
  margin-left: 50px;
  padding-left: 30px;
  border-left: 1px solid #0000001a;
}
</style>
