<template>
  <div class="container p-9">
    <table id="activeRuns" class="display" style="width: 100%">
      <thead>
        <tr>
          <th>StartDate</th>
          <th>Customer</th>
          <th>Run #</th>
          <th>Method</th>
          <th>Status</th>
          <th>Hrs</th>
          <th>Photos</th>
          <th>Results</th>
          <th>Edit</th>
          <th>Delete</th>
          <th>Close</th>
          <th>Re-Open</th>
          <th>Notes</th>
          <th>Reports</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="run in runs" :key="run.id">
          <td class="text-center">{{ run.startDate }}</td>
          <td class="text-center">{{ run.user_id }}</td>
          <td class="text-center">{{ run.id }}</td>
          <td class="text-center">{{ run.plate_methods_id }}</td>
          <td class="text-center">{{ run.status }}</td>
          <td class="text-center">{{ run.status }}</td>
          <!-- Photos action -->
          <td class="text-center">
            <button @click="showPhotos">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </button>
          </td>
          <!-- Result action -->
          <td class="text-center">
            <button @click="showResults">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" >
                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
              </svg>
            </button>
          </td>
          <!-- Edit action -->
          <td class="text-center">
            <button>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
          </td>
          <!-- Delete action -->
          <td class="text-center">
            <button @click="showDelete">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </td>
          <!-- Close action -->
          <td class="text-center">
            <button @click="showClose(run.id)">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </td>
          <!-- ReOpen action -->
          <td class="text-center">
            <button @click="showReOpen(run.id)">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" >
                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd" />
                <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
              </svg>
            </button>
          </td>
          <!-- Notes action -->
          <td class="text-center">
            <button @click="showNotes">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" >
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
              </svg>
            </button>
          </td>
          <!-- Report action -->
          <td class="text-center">
            <button>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </button>
          </td>
          <!-- Email action -->
          <td class="text-center">
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <modal :show="isModalPhotos">
        <div class="container mx-auto p-5">
          <button
            @click="closePhotosModal"
            class="bg-red-600 text-white p-2 rounded-md"
          >
          X
          </button>
          <p class="text-xl font-bold text-center">Lorem ipsum</p>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing</p>
          <ul>
            <li><strong>Name: </strong>Lorem ipsum</li>
            <li><strong>Date added: </strong>22/11/2021</li>
            <li><strong>Hours: </strong>2</li>
            <li>
            <strong>Description: </strong>Lorem ipsum dolor sit amet,
            consectetur adipisicing elit. Nesciunt est sequi beatae at maiores
            ullam vero aut. Laudantium ad blanditiis nisi nemo aliquam quas,
            modi facere dolores aut consequatur commodi!
            </li>
          </ul>

          <div class="mt-5 ">
            <swiper 
            :modules="modules"
            :slides-per-view="1"
            :space-between="50"
            navigation
            :pagination="{ clickable: true }"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
            >
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
              <swiper-slide><img src="https://scontent.fpbc2-3.fna.fbcdn.net/v/t1.6435-9/p600x600/70238220_669796293529180_5931468986758725632_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=730e14&_nc_ohc=uxlVucpTS8AAX_rKFeJ&_nc_ht=scontent.fpbc2-3.fna&oh=9df88e410008751c7d9af7cfaf9078c2&oe=61C20B6E" alt=""></swiper-slide>
            </swiper>
          </div>
        </div>
      </modal>

      <modal :show="isModalResults" >
        <div class="container mx-auto p-5">
          <button
            @click="closeResultsModal"
            class="bg-red-600 text-white p-2 rounded-md"
          >
          X
          </button>
          <p class="text-xl font-bold text-center">Run results</p>
          <p>Enter results for each part in this run</p>
          <hr />
        <div>
          <h3>Run information</h3>
          <ul>
            <li><strong>Run: </strong> 123456</li>
            <li><strong>Customer: </strong> Lorem ipsum</li>
            <li><strong>Start Date: </strong> 22/11/2021</li>
            <li>
            <strong>Description: </strong> Lorem ipsum dolor sit amet
            consectetur adipisicing elit. Impedit placeat dignissimos debitis
            modi pariatur eum vitae incidunt perferendis iure officiis
            assumenda quisquam nobis quia, laudantium, nihil explicabo sit
            dolores rem?
            </li>
          </ul>
        </div>
        <hr />
        <h3>Parts Data</h3>
        <table>
          <thead>
            <tr>
              <th>Part description</th>
              <th>Plate Type</th>
              <th>Chromate</th>
              <th>Topcoat</th>
              <th>Secondary Topcoat</th>
              <th>White Salts</th>
              <th>Red Rust</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>60min SM + 20min HS + 40min Bright - 2 mil</td>
              <td>1-10-2020-2 - 1%</td>
              <td>.5 g/L Pavco Blue Dye 1 - 2 %</td>
              <td>1-71-2 - 3 %</td>
              <td>
                <input type="checkbox" value="false" />
              </td>
              <td>
                <input type="checkbox" value="false" />
              </td>
              <td>
                <button>
                  <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  >
                  <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        </div>
      </modal>

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
            <p>There are 0 notes entered for this run</p>
          </div>
          <hr class="mt-5" />
          <h3 class="font-bold mt-3">Add Note</h3>
          <div class="mt-3">
            <form>
              <label for="public">Public</label> &nbsp;
              <input type="checkbox" id="public" /> <br />
              <textarea name="note" class="mt-4 mb-4" id="note" rows="5"></textarea> <br />
              <button class="bg-green-500 p-2 text-white rounded-md mr-4">Save</button> 
              <button class="bg-red-500 p-2 text-white rounded-md ">Cancel</button>
            </form>
          </div>
        </div>
      </modal>

      <confirmation-modal :show="isModalDelete">
        <template v-slot:title>
          <h1>Are you sure that delete this run?</h1>
        </template>
        <template v-slot:content>
          <button
            class="bg-red-500 p-4 text-white rounded-md mr-4"
            @click="closeDeleteModal"
          >
          Cancel
          </button>
          <button class="bg-green-500 p-4 text-white rounded-md">Acept</button>
        </template>
      </confirmation-modal>

      <confirmation-modal :show="isModalClose">
        <template v-slot:title>
          <h1>Are you sure that close this run?</h1>
        </template>
        <template v-slot:content>
        <button
          class="bg-red-500 p-4 text-white rounded-md mr-4"
          @click="closeCloseModal"
        >
        Cancel
        </button>
        <button
        class="bg-green-500 p-4 text-white rounded-md"
        @click="closeRun"
        >
        Acept
        </button>
      </template>
      </confirmation-modal>

    <confirmation-modal :show="isModalReOpen">
      <template v-slot:title>
        <h1>Are you sure that re open this run?</h1>
      </template>
      <template v-slot:content>
        <button
        class="bg-red-500 p-4 text-white rounded-md mr-4"
        @click="closeReOpenModal"
        >
        Cancel
        </button>
        <button 
        class="bg-green-500 p-4 text-white rounded-md"
        @click="reopenRun"
        >
        Acept
        </button>
      </template>
    </confirmation-modal>
  </div>
</template>

<script>
const $ = require("jquery");
import dt from "datatables.net";
import Modal from "../../../Jetstream/Modal.vue";
import ConfirmationModal from "../../../Jetstream/ConfirmationModal.vue";

import { Navigation, Pagination } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

export default {
  props:['data'],
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    swiper: Swiper,
    swiperSlide: SwiperSlide,
  },
  data() {
    return {
      runs: [],
      isModalPhotos: false,
      isModalResults: false,
      isModalNotes: false,
      isModalDelete: false,
      isModalClose: false,
      isModalReOpen: false,
      id: 0,

    }
  },
  setup() {
    const onSwiper = (swiper) => {
      console.log(swiper);
    };
    const onSlideChange = () => {
      console.log('slide change');
    };
    return {
      onSwiper,
      onSlideChange,
      modules: [Pagination, Navigation]
    };
  },
  methods: {
    showPhotos() {
      this.isModalPhotos = true
    },
    closePhotosModal() {
      this.isModalPhotos = false
    },
    showResults() {
      this.isModalResults = true
    },
    closeResultsModal() {
      this.isModalResults = false
    },
    showNotes() {
      this.isModalNotes = true
    },
    closeNotesModal() {
      this.isModalNotes = false
    },
    showDelete() {
      this.isModalDelete = true
    },
    closeDeleteModal() {
      this.isModalDelete = false
    },
    showClose(id) {
      this.id = id
      this.isModalClose = true
    },
    closeCloseModal() {
      this.isModalClose = false
    },
    showReOpen(id) {
      this.id = id
      console.log(this.id);
      this.isModalReOpen = true
    },
    closeReOpenModal() {
      this.isModalReOpen = false
    },
   
    async closeRun() {
      try {
        const res = await axios.put(`/run/closeRun/${this.id.toString()}`);
        const { ok, message, value } = res.data;
        if (ok) {
          this.isModalClose = false;
          this.gettingData();
          this.id = 0;
        } else {
          console.log("ha ocurrido un error");
        }
      } catch (e) {
        console.log(e);
      }
    },
    async reopenRun() {
      try {
        const res = await axios.put(`/run/reopenRun/${this.id.toString()}`)
        const {ok, message, value} = res.data
        if(ok) {
          this.isModalReOpen = false
          this.gettingData()
          this.id = 1
        }
      } catch (error) {
        console.log(error);
      }
    }
    ,
    async gettingData() {
      try {
        const res = await axios.get('/run/getAllRuns');
        this.runs = res.data.data;
        this.generateDataTable();
        console.log(this.runs);
      } catch (e) {
        console.log(e)
      }
    },
    generateDataTable() {
      this.$nextTick(() => {
        $('#activeRuns').DataTable()
      })
    },
  },
  mounted() {
    this.gettingData()
  },
}
</script>

<style>
</style>