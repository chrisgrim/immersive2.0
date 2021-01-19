<template>
  <div class="modal" v-if="show">
    <div class="modal-content">
      <h1>Add image</h1>
      <header class="tab-header">
        <button @click="tab = 1;" :class="{ active: tab == 1 }">Link</button>
        <button @click="tab = 0;" :class="{ active: tab == 0 }">
          Upload (Drag 'n' Drop)
        </button>
        <button @click="tab = 2;" :class="{ active: tab == 2 }">
          Upload (Simple)
        </button>
      </header>

      <div v-if="tab == 1">
        <p>Here is a test image URL</p>
        <pre>https://i.imgur.com/0ogkTp7.jpg</pre>
        <label for="url">Image URL:</label>
        <input v-model="imageSrc" id="url" />
      </div>
      <div v-if="tab == 2">
        <label for="up">Really simple input upload:</label>
        <input type="file" @change="fileChange" id="up" ref="file" />
      </div>

      <footer class="modal-footer">
        <button
          @click="insertImage"
          class="success"
          :title="validImage ? '' : 'Image URL needs to be valid'"
          :disabled="!validImage"
        >
          Add Image
        </button>
        <button @click="show = false;" class="danger">Close modal</button>
      </footer>
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      imageSrc: "",
      command: null,
      show: false,
      tab: 1,
    };
  },
  computed: {
    validImage() {
      return (
        this.imageSrc.match(/unsplash/) !== null ||
        this.imageSrc.match(/\.(jpeg|jpg|gif|png)$/) != null
      );
    }
  },
  methods: {
    showModal(command) {
      // Add the sent command
      this.command = command;
      this.show = true;
    },
    vfileUploaded(file) {
      alert("Your image has been uploaded to the server");
      alert("NOTE THIS IS A DUMMY DEMO, THERE IS NO BACKEND");

      /** Here is where you get your URL/Base64 string or what ever.*/

      this.imageSrc = "https://source.unsplash.com/random/400x100";
    },

    fileChange(e) {
      const file = this.$refs.file.files[0];
      const uploadUrl = `https://httpbin.org/post`;
      let formData = new FormData();

      formData.append("file", this.file);

      console.log("Uploading...");

      axios.post(uploadUrl).then(data => {
        // Take the URL/Base64 from `data` returned from server
        alert("Your image has been uploaded to the server");
        alert("NOTE THIS IS A DUMMY DEMO, THERE IS NO BACKEND");

        this.imageSrc = "https://source.unsplash.com/random/400x100";
      });
    },
    insertImage() {
      const data = {
        command: this.command,
        data: {
          src: this.imageSrc
          // alt: "YOU CAN ADD ALT",
          // title: "YOU CAN ADD TITLE"
        }
      };

      this.$emit("onConfirm", data);
      this.closeModal();
    },

    closeModal() {
      this.show = false;
      this.imageSrc = "";
      this.tab = 1;
    }
  }
};
</script>
