<template>
  <modal :show="isModalNotes">
    <div class="container mx-auto p-5 relative">
      <button
        @click="closeNotesModal"
        class="absolute right-10 hover:text-red-400"
      >
        X
      </button>
      <p class="text-xl font-bold text-center">Run notes</p>
      <p class="mb-5 mt-2">
        The note editor is used for viewing / adding notes to a run
      </p>
      <hr />
      <div class="mt-3 shadow-lg rounded-lg p-5">
        <h3 class="font-bold  text-lg">Notes</h3>
        <div class="h-full">
          <ul class="h-80 overflow-y-auto">
            <li v-for="(note, index) in notes" :key="index">{{ note.note }}</li>
          </ul>
        </div>
      </div>
      <hr class="mt-5" />
      <h3 class="font-bold mt-3">Add Note</h3>
      <div class="mt-3">
        <form ref="noteForm" @submit.prevent="submitNote">
          <label for="public">Public</label> &nbsp;
          <input type="checkbox" id="public" v-model="form.isPublic" /> <br />
          <div>
            <textarea
              name="note"
              class="mt-4 w-full"
              id="note"
              rows="5"
              v-model="form.note"
            ></textarea>
            <br />
            <p
              v-for="error in v$.note.$errors"
              :key="error.$uid"
              class="text-red-400 p-4"
            >
              {{ error.$message }}
            </p>
          </div>
          <div class="flex justify-around gap-5">
            <button
              type="button"
              @click="closeNotesModal"
              class="bg-red-500 p-2 text-white rounded-md w-full"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-green-500 p-2 text-white rounded-md mr-4 w-full"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </modal>
</template>

<script>
import { ref, onMounted, reactive } from "vue";
import { required } from "@vuelidate/validators";
import Modal from "../../../Jetstream/Modal.vue";
import useVuelidate from "@vuelidate/core";
import useHelper from "@/composables/useHelper";

export default {
  props: {
    isModalNotes: {
      type: Boolean,
      default: false,
    },
    run: {
      type: Object,
      default: {},
    },
  },
  emits: ["closeModal", "noteAdded"],
  components: {
    modal: Modal,
  },
  setup(props, { emit }) {
    const { makeToast } = useHelper();
    const { run } = props;
    let notes = ref(run.notes);
    const id = ref(run.id)
    let noteData = ref({
      id: 0,
      note: "",
      run_id: id,
      part_id: null,
    });

    const form = reactive({
      id: 0,
      isPublic: 0,
      note: "",
      run_id: id,
      part_id: null,
    });

    const closeNotesModal = () => emit("closeModal");

    const noteAdded = () => emit("noteAdded");

    const rules = {
      note: {
        required,
      },
    };
    const v$ = useVuelidate(rules, form);
    const submitNote = async () => {
      try {
        const isFormCorrect = await v$.value.$validate();
        if (!isFormCorrect) return;
        noteData.value.note = form.note;
        noteData.value.run_id = id;
        noteData.value.isPublic = form.isPublic ? 1 : 0;
        const res = await axios.post("/note/add", noteData._value);
        console.log(res);
        const { ok, message, value } = res.data;
        if (ok) {
          notes.value.push({ note: value.note });
          console.log(notes);
          console.log(value);
          makeToast(message);
          v$.value.$reset();
          form.id= 0
          form.note = "";
          form.isPublic = false;
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        console.log(e);
        makeToast(e, "error");
      }
    };
    return {
      form,
      v$,
      closeNotesModal,
      noteAdded,
      submitNote,
      notes,
    };
  },
  watch: {
    run() {
      this.notes = this.run.notes;
      this.form.run_id= this.run.id
    },
  },
};
</script>

<style>
</style>