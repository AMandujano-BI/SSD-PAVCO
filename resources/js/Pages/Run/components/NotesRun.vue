<template>
   <modal :show="isModalNotes">
        <div class="container mx-auto p-5">
          <button
            @click="closeNotesModal"
            class="bg-red-600 text-white p-2 rounded-md"
          >
          X
          </button>
          <p class="text-xl font-bold text-center">Run notes</p>
          <p class="mb-5 mt-2">The note editor is used for viewing / adding notes to a run</p>
          <hr />
          <div class="mt-3">
            <h3 class="font-bold">Notes</h3>
            <div >
              <u>
                <li v-for="(note, index) in notes" :key="index"> {{note}} </li>
              </u>
            </div>
          </div>
          <hr class="mt-5" />
          <h3 class="font-bold mt-3">Add Note</h3>
          <div class="mt-3">
            <form ref="noteForm" @submit.prevent="submit">
              <label for="public">Public</label> &nbsp;
              <input type="checkbox" id="public" v-model="form.isPublic" /> <br />
              
              <textarea name="note" class="mt-4 mb-4" id="note" rows="5" v-model="form.note"></textarea> <br />
              <p v-for="error in v$.form.note.$errors" :key="error.$uid" class="text-red-400"></p>
              <button type="submit" class="bg-green-500 p-2 text-white rounded-md mr-4">Save</button> 
              <button type="button" @click="closeNotesModal" class="bg-red-500 p-2 text-white rounded-md ">Cancel</button>
            </form>
          </div>
        </div>
    </modal>
</template>

<script>
import { ref, onMounted } from "vue";
import { required } from "@vuelidate/validators";
import Modal from '../../../Jetstream/Modal.vue';
import useVuelidate from "@vuelidate/core";
import useHelper from "@/composables/useHelper";

export default {
    props: {
        isModalNotes: {
            type: Boolean,
            default: false
        },
        run: {
          type: Object,
          default: {}
        }
    },
    emits: ['closeModal', 'noteAdded'],
    components: {
        modal: Modal
    },
    setup(props, {emit}){ 
      const { makeToast } = useHelper()
      const {run} = props
      let notes = ref(run.notes)
      let noteData = ref(
        {
        startDate: '',
        number: '',
        note: '',
        plate_types_id: '',
        primaryCoatId: '',
        isPublic: '',
        }
      )
      
      const form = ref({
        isPublic: 0,
        note: ''
      });
      
      const closeNotesModal = () => {
        // makeToast("Note was closed")
        emit('closeModal');
      };
      const noteAdded = () => {
        emit('noteAdded');
      };
      const submit = () => {
        console.log(form, 'submit');
        noteData.value.startDate = run.startDate
        noteData.value.number = run.number.toString()
        noteData.value.note = form._value.note
        noteData.value.plate_types_id = run.plate_types_id.toString()
        noteData.value.primaryCoatId = run.primaryCoatId.toString()
        noteData.value.isPublic = form._value.isPublic?1:0
        axios.post('/note/add', noteData._value)
          .then(res => {
            console.log(res);
            if(res.data.ok) {
              notes.value.push(form._value.note)
              makeToast("Note was added successfully")
            }
          })
          .catch((e) => {
            console.log(e);
          }) 
        // this.noteAdded()
      }
      return {
          v$: useVuelidate(),
          form,
          closeNotesModal,
          noteAdded,
          submit,
          notes
      };
    },
    validations: {
        form: {
          isPublic: {
            required,
          },
          note: {
            required,
          },
        },
    },
    watch: {
      'run'() {
        this.notes = this.run.notes
      }
    }
}
</script>

<style>

</style>