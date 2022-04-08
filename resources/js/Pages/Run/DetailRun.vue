<template>
  <app-layout title="Detail Run">
    <div class="container mx-auto pt-14">
      <div class="container p-2">
        <div class="flex items-center md:justify-center">
          <div
            class="
              inline-flex
              md:hidden
              bg-[#34689c]
              text-[20px]
              rounded-[7px]
              p-5
              w-[50px]
              h-[50px]
              items-center
              justify-center
              cursor-pointer
            "
            @click="goBack"
          >
            <svg
              width="50"
              height="50"
              viewBox="0 0 50 50"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M35 12.5L22.5 25L35 37.5L32.5 42.5L15 25L32.5 7.5L35 12.5Z"
                fill="white"
              />
            </svg>
          </div>

          <h1
            class="
              text-center text-2xl
              flex-1
              md:flex-none
              p-5
              font-bold
              text-[#3b4559]
            "
          >
            Run # {{ runDetail.id }}
          </h1>
        </div>
        <div
          class="
            grid grid-cols-2
            gap-4
            items-center
            sm:grid-cols-3
            lg:grid-cols-4
            py-5
          "
        >
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline">
              Start Date
            </span>
            <span> {{ startDate }} </span>
          </p>
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Status
            </span>
            <span> {{ runDetail.status == 0 ? "Active" : "Complete" }} </span>
          </p>
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Customer
            </span>
            <span> {{ runDetail.company?.name }} </span>
          </p>
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Hrs
            </span>
            <span>
              {{ runDetail.hours }}
            </span>
          </p>
          <p class="text-[#3b4559] text-[16px] font-semibold">
            <span class="text-[#838A97] font-light block py-2 md:inline"
              >Method
            </span>
            <span> {{ runDetail.method?.name }} </span>
          </p>
        </div>

        <!-- BUTTONS FOR SUPER ADMIN-->
        <div
          class="
            grid grid-cols-2
            gap-4
            items-center
            sm:grid-cols-3
            lg:grid-cols-4
            py-5
          "
          v-if="
            $page.props.auth.rols[0].id == 1 || $page.props.auth.rols[0].id == 2
          "
        >
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#75DDDD]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="showPhotos"
          >
            <icon-photo />
            Photos
          </div>

          <div
            class="
              flex
              gap-2
              items-center
              bg-[#227D9E]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="editRun"
          >
            <icon-edit />
            Edit
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#0271C5]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="showDelete"
          >
            <icon-delete />
            Delete
          </div>
          <button
            class="
              flex
              gap-2
              items-center
              bg-[#17C4B2]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            :class="{ 'bg-[#F0F0F0]': runDetail.status == 1 }"
            @click="showClose"
            :disabled="runDetail.status == 1"
          >
            <div v-if="runDetail.status == 1">
              <svg
                width="25"
                height="25"
                viewBox="0 0 40 40"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g fill="none" fill-rule="evenodd">
                  <path fill="#F0F0F0" d="M0 0h40v40H0z" />
                  <path
                    stroke="#9CA3AF"
                    stroke-linecap="round"
                    d="m11.2 11.2 17.806 17.806M29.006 11.2 11.2 29.006"
                  />
                </g>
              </svg>
            </div>

            <icon-close v-if="runDetail.status == 0" />
            <span :class="{ 'text-gray-400': runDetail.status == 1 }">
              Close
            </span>
          </button>
          <button
            class="
              flex
              gap-2
              items-center
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="showReOpen"
            :class="{
              'bg-[#F0F0F0]': runDetail.status == 0,
              'bg-[#0ABC8A]': runDetail.status == 1,
            }"
            :disabled="runDetail.status == 0"
          >
            <div v-if="runDetail.status == 0">
              <svg
                width="25"
                height="25"
                viewBox="0 0 40 40"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g fill="none" fill-rule="evenodd">
                  <path fill="#F0F0F0" d="M0 0h40v40H0z" />
                  <rect
                    stroke="#9CA3AF"
                    x="10.1"
                    y="10.1"
                    width="13.4"
                    height="13.4"
                    rx="1"
                  />
                  <rect
                    stroke="#9CA3AF"
                    x="18.1"
                    y="18.1"
                    width="13.4"
                    height="13.4"
                    rx="1"
                  />
                </g>
              </svg>
            </div>
            <icon-re-open v-if="runDetail.status == 1" />
            <span :class="{ 'text-gray-400': runDetail.status == 0 }">
              Re-Open
            </span>
          </button>

          <div
            class="
              flex
              gap-2
              items-center
              bg-[#024CC5]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="reportRun"
          >
            <svg
              width="25"
              height="25"
              viewBox="0 0 25 25"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g fill="none" fill-rule="evenodd">
                <path fill="#024CC5" d="M0 0h25v25H0z" />
                <g fill="#FFF" fill-rule="nonzero">
                  <path
                    d="M11.586 9.915a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.728a.563.563 0 0 0-.796.796L8.54 12.96a.565.565 0 0 0 .795.001l2.25-2.25a.563.563 0 0 0 0-.796zM11.586 14.415a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.727a.563.563 0 0 0-.796.796L8.54 17.46a.56.56 0 0 0 .796 0l2.25-2.25a.563.563 0 0 0 0-.796zM15.688 10.875h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125zM15.688 15.375h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125z"
                  />
                  <path
                    d="M17.375 5.25h-2.25v-.563a.563.563 0 0 0-.563-.562h-1.22A1.692 1.692 0 0 0 11.75 3c-.733 0-1.359.47-1.592 1.125h-1.22a.563.563 0 0 0-.563.563v.562h-2.25C5.505 5.25 5 5.755 5 6.375v13.5C5 20.495 5.505 21 6.125 21h11.25c.62 0 1.125-.505 1.125-1.125v-13.5c0-.62-.505-1.125-1.125-1.125zm-7.875 0h1.125c.31 0 .563-.252.563-.563a.563.563 0 0 1 1.124 0c0 .311.252.563.563.563H14v1.125H9.5V5.25zm7.875 14.625H6.125v-13.5h2.25v.563c0 .31.252.562.563.562h5.624c.311 0 .563-.252.563-.563v-.562h2.25v13.5z"
                  />
                </g>
              </g>
            </svg>
            Report
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#1E385E]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="reportAndPhotosRun"
          >
            <svg
              width="25"
              height="25"
              viewBox="0 0 25 25"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g fill="none" fill-rule="evenodd">
                <path fill="#1E385E" d="M0 0h25v25H0z" />
                <g fill="#FFF" fill-rule="nonzero">
                  <path
                    d="M14.584 12.249a3.253 3.253 0 0 0-3.249 3.25 3.253 3.253 0 0 0 3.25 3.249 3.253 3.253 0 0 0 3.249-3.25 3.253 3.253 0 0 0-3.25-3.249zm0 5.308a2.061 2.061 0 0 1-1.814-3.03l1.393 1.393a.596.596 0 0 0 .421.174h1.97a2.062 2.062 0 0 1-1.97 1.463zm.247-2.654-1.219-1.219a2.062 2.062 0 0 1 2.942 1.219h-1.723z"
                  />
                  <path
                    d="M18.09 4.77h-1.712a1.414 1.414 0 0 0-1.31-.885h-.925c-.23-.52-.75-.885-1.355-.885-.604 0-1.125.364-1.355.885h-.925c-.592 0-1.1.366-1.309.884H7.486C6.666 4.77 6 5.436 6 6.255v13.26C6 20.333 6.667 21 7.486 21H18.09c.819 0 1.486-.666 1.486-1.486V6.255c0-.82-.667-1.486-1.486-1.486zm-7.803.527c0-.122.1-.221.221-.221h1.396a.595.595 0 0 0 .595-.596.29.29 0 0 1 .578 0c0 .329.267.596.596.596h1.395c.122 0 .221.099.221.22v1.022c0 .122-.1.22-.221.22h-4.56a.221.221 0 0 1-.22-.22V5.297zm7.804 14.512H7.486a.295.295 0 0 1-.295-.295V6.255c0-.163.132-.295.295-.295h1.61v.358c0 .778.634 1.412 1.412 1.412h4.56c.779 0 1.412-.634 1.412-1.412V5.96h1.61c.163 0 .296.132.296.295v13.26a.295.295 0 0 1-.295.294z"
                  />
                  <path
                    d="M8.365 9.901h4.423a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM8.365 11.67h2.654a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM11.614 12.844a.595.595 0 0 0-.595-.595H8.365a.595.595 0 0 0 0 1.19h2.654a.595.595 0 0 0 .595-.595z"
                  />
                </g>
              </g>
            </svg>
            Report
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#50BEFF]
              text-white
              py-4
              justify-center
              cursor-pointer
            "
            @click="openModalEmail"
          >
            <icon-email />
            E-mail
          </div>
        </div>

        <!-- BUTTONS FOR ADMIN,CUSTOMER,DISTRIBUTOR-->
        <div
          class="
            grid grid-cols-2
            gap-4
            items-center
            sm:grid-cols-3
            lg:grid-cols-4
            py-5
          "
          v-if="
            $page.props.auth.rols[0].id != 1 && $page.props.auth.rols[0].id != 2
          "
        >
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#75DDDD]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="showPhotos"
          >
            <icon-photo />
            Photos
          </div>

          <div
            class="
              flex
              gap-2
              items-center
              bg-[#024CC5]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="reportRun"
          >
            <svg
              width="25"
              height="25"
              viewBox="0 0 25 25"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g fill="none" fill-rule="evenodd">
                <path fill="#024CC5" d="M0 0h25v25H0z" />
                <g fill="#FFF" fill-rule="nonzero">
                  <path
                    d="M11.586 9.915a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.728a.563.563 0 0 0-.796.796L8.54 12.96a.565.565 0 0 0 .795.001l2.25-2.25a.563.563 0 0 0 0-.796zM11.586 14.415a.564.564 0 0 0-.797 0l-1.851 1.852-.727-.727a.563.563 0 0 0-.796.796L8.54 17.46a.56.56 0 0 0 .796 0l2.25-2.25a.563.563 0 0 0 0-.796zM15.688 10.875h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125zM15.688 15.375h-2.25a.563.563 0 0 0 0 1.125h2.25a.563.563 0 0 0 0-1.125z"
                  />
                  <path
                    d="M17.375 5.25h-2.25v-.563a.563.563 0 0 0-.563-.562h-1.22A1.692 1.692 0 0 0 11.75 3c-.733 0-1.359.47-1.592 1.125h-1.22a.563.563 0 0 0-.563.563v.562h-2.25C5.505 5.25 5 5.755 5 6.375v13.5C5 20.495 5.505 21 6.125 21h11.25c.62 0 1.125-.505 1.125-1.125v-13.5c0-.62-.505-1.125-1.125-1.125zm-7.875 0h1.125c.31 0 .563-.252.563-.563a.563.563 0 0 1 1.124 0c0 .311.252.563.563.563H14v1.125H9.5V5.25zm7.875 14.625H6.125v-13.5h2.25v.563c0 .31.252.562.563.562h5.624c.311 0 .563-.252.563-.563v-.562h2.25v13.5z"
                  />
                </g>
              </g>
            </svg>
            Report
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#1E385E]
              cursor-pointer
              text-white
              py-4
              justify-center
            "
            @click="reportAndPhotosRun"
          >
            <svg
              width="25"
              height="25"
              viewBox="0 0 25 25"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g fill="none" fill-rule="evenodd">
                <path fill="#1E385E" d="M0 0h25v25H0z" />
                <g fill="#FFF" fill-rule="nonzero">
                  <path
                    d="M14.584 12.249a3.253 3.253 0 0 0-3.249 3.25 3.253 3.253 0 0 0 3.25 3.249 3.253 3.253 0 0 0 3.249-3.25 3.253 3.253 0 0 0-3.25-3.249zm0 5.308a2.061 2.061 0 0 1-1.814-3.03l1.393 1.393a.596.596 0 0 0 .421.174h1.97a2.062 2.062 0 0 1-1.97 1.463zm.247-2.654-1.219-1.219a2.062 2.062 0 0 1 2.942 1.219h-1.723z"
                  />
                  <path
                    d="M18.09 4.77h-1.712a1.414 1.414 0 0 0-1.31-.885h-.925c-.23-.52-.75-.885-1.355-.885-.604 0-1.125.364-1.355.885h-.925c-.592 0-1.1.366-1.309.884H7.486C6.666 4.77 6 5.436 6 6.255v13.26C6 20.333 6.667 21 7.486 21H18.09c.819 0 1.486-.666 1.486-1.486V6.255c0-.82-.667-1.486-1.486-1.486zm-7.803.527c0-.122.1-.221.221-.221h1.396a.595.595 0 0 0 .595-.596.29.29 0 0 1 .578 0c0 .329.267.596.596.596h1.395c.122 0 .221.099.221.22v1.022c0 .122-.1.22-.221.22h-4.56a.221.221 0 0 1-.22-.22V5.297zm7.804 14.512H7.486a.295.295 0 0 1-.295-.295V6.255c0-.163.132-.295.295-.295h1.61v.358c0 .778.634 1.412 1.412 1.412h4.56c.779 0 1.412-.634 1.412-1.412V5.96h1.61c.163 0 .296.132.296.295v13.26a.295.295 0 0 1-.295.294z"
                  />
                  <path
                    d="M8.365 9.901h4.423a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM8.365 11.67h2.654a.595.595 0 0 0 0-1.19H8.365a.595.595 0 0 0 0 1.19zM11.614 12.844a.595.595 0 0 0-.595-.595H8.365a.595.595 0 0 0 0 1.19h2.654a.595.595 0 0 0 .595-.595z"
                  />
                </g>
              </g>
            </svg>
            Report
          </div>
          <div
            class="
              flex
              gap-2
              items-center
              bg-[#50BEFF]
              text-white
              py-4
              justify-center
              cursor-pointer
            "
            @click="openModalEmail"
          >
            <icon-email />
            E-mail
          </div>
        </div>

        <div class="flex gap-2 items-center flex-1 w-full mb-5">
            <div
          v-if="
            $page.props.auth.rols[0].id == 1 || $page.props.auth.rols[0].id == 2
          "
        >
            <button
              class="rounded py-4 text-white px-3 mt-5"
              @click="showSaveWsRs"
              v-if="!loading"
              :class="{
                'bg-[#F0F0F0]': runDetail.status == 1 || !isWsRsChanges,
                'bg-primary': runDetail.status == 0,
              }"
              :disabled="runDetail.status == 1 || !isWsRsChanges"
            >
              <span
                :class="{
                  'text-gray-400': runDetail.status == 1 || !isWsRsChanges,
                }"
              >
                Save
              </span>
            </button>
            <button
              class="bg-primary rounded py-4 text-white px-3 mt-5"
              v-if="loading"
              disabled
            >
              <div
                className="animate-spin rounded-full h-6 w-6 border-b-2 border-t-2 border-white inline-block"
              ></div>
            </button>
          </div>
          <input
            type="text"
            class="
              py-[14px]
              text-sm
              w-full
              pl-10
              rounded-sm
              border-[#a2a2a2]
              placeholder-[#a2a2a2]
              text-[#333]
              mt-5
            "
            id="filterRunPartInputBot"
            placeholder="Search Parts..."
            autocomplete="off"
          />
        </div>

        <table
          id="activeRunsDetail"
          class="display"
          style="width: 100%"
          data-ordering="false"
        >
          <thead>
            <tr>
              <th class="no-sort" data-priority="1">Part description</th>
              <th>Plate Type</th>
              <th>Chromate</th>
              <th>Topcoat</th>
              <th>Secondary Topcoat</th>
              <th data-priority="2">White Salt</th>
              <th data-priority="2">Red Rust</th>
              <!-- <th class="no-sort">Notes</th> -->
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        <div></div>
        <modal :show="isModalPhotos" @close="closePhotosModal">
          <div class="container mx-auto p-5 relative bg-[#ebf2fd]">
            <button @click="closePhotosModal" class="absolute right-5">
              X
            </button>
            <photos-run :id="id" />
          </div>
        </modal>
        <modal :show="modalEmail" @close="closeModalEmail">
          <div class="p-5">
            <form-email
              :emailSend="emailSend"
              :id="id"
              @closeModal="closeModalEmail"
            />
          </div>
        </modal>
        <confirmation-modal :show="isModalDelete">
          <template v-slot:title>
            <h1>Are you sure that you want to delete this run #{{ id }}?</h1>
          </template>
          <template v-slot:content>
            <div class="flex justify-center gap-4 items-center">
              <button
                class="bg-red-500 p-4 text-white rounded-md mr-4"
                @click="closeDeleteModal"
              >
                Cancel
              </button>
              <button
                class="bg-green-500 p-4 text-white rounded-md"
                @click="deleteRun"
              >
                Acept
              </button>
            </div>
          </template>
        </confirmation-modal>

        <confirmation-modal :show="isModalClose">
          <template v-slot:title>
            <h1>Are you sure that you want to close this run #{{ id }}?</h1>
          </template>
          <template v-slot:content>
            <div class="flex justify-center gap-4 items-center">
              <button
                class="bg-red-500 p-4 text-white rounded-md mr-4 min-w-[400]"
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
            </div>
          </template>
        </confirmation-modal>

        <confirmation-modal :show="isModalReOpen">
          <template v-slot:title>
            <h1>Are you sure that you want to re open this run #{{ id }}</h1>
          </template>
          <template v-slot:content>
            <div class="flex justify-center gap-4 items-center">
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
            </div>
          </template>
        </confirmation-modal>

        <confirmation-modal :show="isModalSaveWsRs">
          <template v-slot:title>
            <h1>Are you sure that you want to apply this changes?</h1>
          </template>
          <template v-slot:content>
            <div class="flex justify-center gap-4 items-center">
              <button
                class="bg-red-500 p-4 text-white rounded-md mr-4"
                @click="closeSaveWsRs"
              >
                Cancel
              </button>
              <button
                class="bg-green-500 p-4 text-white rounded-md"
                @click="updateWsRs"
              >
                Acept
              </button>
            </div>
          </template>
        </confirmation-modal>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { reactive, nextTick, ref } from "vue";
const $ = require("jquery");
var dt = require("datatables.net");
import "datatables.net-responsive-dt";
import "datatables.net-rowreorder-dt";
import IconEmail from "@/assets/Icons/iconEmail.vue";
import IconEdit from "@/assets/Icons/iconEdit.vue";
import IconDelete from "@/assets/Icons/iconDelete.vue";
import IconReOpen from "@/assets/Icons/iconReopen.vue";
import IconResult from "@/assets/Icons/iconResult.vue";
import IconPhoto from "@/assets/Icons/iconPhoto.vue";
import IconClose from "@/assets/Icons/iconClose.vue";
import Modal from "../../Jetstream/Modal.vue";
import FormEmail from "./components/FormEmail.vue";
import useHelper from "@/composables/useHelper";
import ConfirmationModal from "../../Jetstream/ConfirmationModal.vue";
import PhotosRun from "./components/PhotosRun.vue";
import axios from "axios";

export default {
  components: {
    modal: Modal,
    confirmationModal: ConfirmationModal,
    AppLayout: AppLayout,
    photosRun: PhotosRun,
    IconEmail,
    FormEmail,
    IconEdit,
    IconDelete,
    IconReOpen,
    IconClose,
    IconResult,
    IconPhoto,
    IconDelete,
    IconEdit,
  },
  props: ["run"],
  setup() {
    const id = Inertia.page.url.split("/")[3];
    let parts = ref([]);
    const { makeToast } = useHelper();
    const runDetail = ref({});
    const startDate = ref("");
    const modalEmail = ref(false);
    const emailSend = ref("");
    const isModalDelete = ref(false);
    const isModalClose = ref(false);
    const isModalReOpen = ref(false);
    const isModalPhotos = ref(false);
    const isModalSaveWsRs = ref(false);
    const loading = ref(false);
    const isWsRsChanges = ref(false);

    const generateForm = async () => {
      const res = await axios.get(`/part/getPartsByRun/${runDetail.value.id}`);
      parts.value = [];
      // console.log(res.data.data);
      res.data.data.forEach((element) => {
        parts.value.push({
          id: element.id,
          ws: element.isWs === null ? false : element.isWs,
          rs: element.isRs === null ? false : element.isRs,
          hoursWs: element.hoursWs,
          hoursRs: element.hoursRs,
        });
      });
      // console.log(parts.value);
    };

    const gettingData = async () => {
      try {
        const res = await axios.get(`/run/${id}`);
        runDetail.value = res.data;
        startDate.value = runDetail.value.start_date.slice(0, 10);

        generateForm();

        $("#activeRunsDetail").DataTable().clear().destroy();
        await generateDataTableDetail();
      } catch (e) {
        console.log(e);
      }
    };

    const closePhotosModal = () => (isModalPhotos.value = false);
    // Results
    const showResults = (id) => Inertia.get(`/run/detail/${id}`);
    const closeDeleteModal = () => (isModalDelete.value = false);
    const editRun = () => {
      Inertia.get(`/part/${id}`);
    };
    const showReOpen = (id) => {
      isModalReOpen.value = true;
    };
    const reopenRun = async () => {
      try {
        const res = await axios.put(`/run/reopenRun/${id}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalReOpen.value = false;
          isWsRsChanges.value = false;
          gettingData();
          makeToast(message);
          runDetail.value.status = 0;
          generateForm();
          $("#activeRunsDetail").DataTable().clear().destroy();
          await generateDataTableDetail();
        } else {
          makeToast(message, "error");
        }
      } catch (error) {
        console.log(error);
      }
    };
    const deleteRun = async () => {
      try {
        const res = await axios.delete(`/run/deleteRun/${id}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalDelete.value = false;
          makeToast(message);
          Inertia.get("/run");
        } else {
          makeToast(message, "error");
        }
      } catch (error) {
        console.log(error);
      }
    };
    const closeRun = async () => {
      try {
        const res = await axios.put(`/run/closeRun/${id}`);
        const { ok, message, value } = res.data;
        if (ok) {
          isModalClose.value = false;
          gettingData();
          makeToast(message);

          runDetail.value.status = 1;
          // generateForm();
          $("#activeRunsDetail").DataTable().clear().destroy();
          await generateDataTableDetail();
        } else {
          makeToast(message, "error");
        }
      } catch (e) {
        console.log(e);
      }
    };

    const showPhotos = (id) => {
      //findRun(id);
      isModalPhotos.value = true;
    };
    const closeModalEmail = () => {
      modalEmail.value = false;
    };
    const reportRun = () => {
      let currentTimeZone = `${
        Intl.DateTimeFormat().resolvedOptions().timeZone
      }`;
      currentTimeZone = currentTimeZone.replace("/", "----");
      window.location.href = `/run/download/${id}/'${currentTimeZone}'`;
    };
    const reportAndPhotosRun = () => {
      let currentTimeZone = `${
        Intl.DateTimeFormat().resolvedOptions().timeZone
      }`;
      currentTimeZone = currentTimeZone.replace("/", "----");
      window.location.href = `/run/downloadPlus/${id}/'${currentTimeZone}'`;
    };
    const openModalEmail = (id) => {
      modalEmail.value = true;
    };
    const showDelete = () => {
      isModalDelete.value = true;
    };
    const showClose = (id) => {
      isModalClose.value = true;
    };
    const showSaveWsRs = () => {
      if (runDetail.value.status === 1) {
        return;
      }
      isModalSaveWsRs.value = true;
    };
    const closeSaveWsRs = () => {
      isModalSaveWsRs.value = false;
    };

    const goBack = () => {
      Inertia.get("/run");
    };
    $(document).ready(function () {
      $("#filterRunPartInputBot")
        .off()
        .keyup(function () {
          $("#activeRunsDetail").DataTable().search(this.value).draw();
        });
    });
    const generateDataTableDetail = () => {
      nextTick(() => {
        $("#activeRunsDetail").DataTable({
          ordering: true,
          bLengthChange: false,
          pageLength: 10,
          stateSave: true,
          order: [[1, "desc"]],
          columnDefs: [
            {
              defaultContent: "-",
              targets: "_all",
            },
          ],
          responsive: true,
          language: {
            paginate: {
              next: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(270 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '→'
              previous: `<svg class="arrow_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="15" height="14" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><g transform="rotate(90 10 10)"><path d="M5 6l5 5l5-5l2 1l-7 7l-7-7z" fill="white"/></g></svg>`, // or '←'
            },
            info: "Showing results _START_ to _END_ from _TOTAL_",
          },
          ajax: {
            url: `/part/getPartsByRun/${runDetail.value.id}`,
          },
          stateSaveCallback: function (settings, data) {
            const state = settings.aoData;
            let arr = [];
            state.forEach((element) => {
              arr.push(element._aData);
            });
          },
          columns: [
            {
              name: "description",
              searchable: true,
              render: function (data, type, row, meta) {
                if (row.description != null) {
                  return "<td>" + row.description + "</td>";
                }
                return "<td></td>";
              },
            },
            {
              name: "plate_type.name",
              searchable: true,
              render: function (data, type, row, meta) {
                if (row.plate_type?.name != undefined) {
                  return `<td>${row.plate_type?.name} - ${row.plateThick} ${row.typePlateThick ==1 ?'microns':(row.typePlateThick ==2?'mils':'')} </td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "part.chromate.name",
              searchable: true,
              render: function (data, type, row, meta) {
                if (row.chromate?.name != undefined) {
                  return `<td> ${row.chromate?.name}  ${
                    row.primaryPer != null ? "/ " + row.primaryPer + " %" : ""
                  } ${
                    row.primaryTemp != null
                      ? "/ " + row.primaryTemp + " °F"
                      : ""
                  } ${
                    row.primaryPH != null ? " /" + row.primaryPH + " pH" : ""
                  } ${
                    row.primaryDiptime != null
                      ? " / " + row.primaryDiptime + " sec"
                      : ""
                  } </td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "part.top_coat.name",
              searchable: true,
              render: function (data, type, row, meta) {
                if (row.top_coat?.name != undefined) {
                  return `<td> ${row.top_coat?.name}  ${
                    row.topCoatPer != null ? "/ " + row.topCoatPer + " %" : ""
                  } ${
                    row.topCoatTemp != null
                      ? "/ " + row.topCoatTemp + " °F"
                      : ""
                  } ${
                    row.topCoatPH != null ? " /" + row.topCoatPH + " pH" : ""
                  } ${
                    row.topCoatDiptime != null
                      ? " / " + row.topCoatDiptime + " sec"
                      : ""
                  } </td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "part.coat.name",
              searchable: true,
              render: function (data, type, row, meta) {
                if (row.coat?.name != undefined) {
                  return `<td> ${row.coat?.name}  ${
                    row.coatPer != null ? "/ " + row.coatPer + " %" : ""
                  } ${
                    row.coatTemp != null ? "/ " + row.coatTemp + " °F" : ""
                  } ${row.coatPH != null ? " /" + row.coatPH + " pH" : ""} ${
                    row.coatDiptime != null
                      ? " / " + row.coatDiptime + " sec"
                      : ""
                  } </td>`;
                }
                return "<td></td>";
              },
            },
            {
              name: "whitesalt",
              searchable: false,
              render: function (data, type, row, meta) {
                let whiteSaltInput;

                if (runDetail.value.status === 1) {
                  // está cerrado
                  if (row.isWs !== null && row.isWs) {
                    whiteSaltInput = `${row.hoursWs} hrs`;
                  } else {
                    whiteSaltInput = `Removed at ${runDetail.value.hoursClosed} hrs`;
                  }
                } else {
                  // está abierto
                  if (row.isWs !== null && row.isWs) {
                    // Si tiene horas, se renderiza horas
                    whiteSaltInput =
                      '<input type="number" id="hws' +
                      row.id +
                      '" class="whitesInp" itemId=' +
                      row.id +
                      " value=" +
                      row.hoursWs +
                      ' style="width: 60px"> hrs';
                  } else {
                    // Si no tiene horas, se colocará input value = false
                    whiteSaltInput =
                      '<input type="checkbox" id="ws' +
                      row.id +
                      '" class="whitesChk" itemId=' +
                      row.id +
                      ' value="false">';
                  }
                }

                return whiteSaltInput;
              },
            },
            {
              name: "redrust",
              searchable: false,
              render: function (data, type, row, meta) {
                let redRustInput;
                if (runDetail.value.status === 1) {
                  // está cerrado
                  if (row.isRs !== null && row.isRs) {
                    redRustInput = `${row.hoursRs} hrs`;
                  } else {
                    redRustInput = `Removed at ${runDetail.value.hoursClosed} hrs`;
                  }
                } else {
                  // está abierto
                  if (row.isRs !== null && row.isRs) {
                    redRustInput =
                      '<input type="number" id="hrss' +
                      row.id +
                      '" class="redrInp" itemId=' +
                      row.id +
                      " value=" +
                      row.hoursRs +
                      ' style="width: 60px;"> hrs';
                  } else {
                    redRustInput =
                      '<input type="checkbox" id="rs' +
                      row.id +
                      '" class="redrChk" itemId=' +
                      row.id +
                      ' value="false">';
                  }
                }

                return redRustInput;
              },
            },
          ],
          drawCallback: function () {
            $("#activeRunsDetail").on(
              "click",
              "[class*=whitesChk]",
              function (e) {
                const idWs = e.currentTarget.attributes[1].value;
                const wsCheck = $(`#${idWs}`).is(":checked");
                whiteSalt(e.currentTarget.attributes[3].value, wsCheck);
              }
            );
            $("#activeRunsDetail").on(
              "click",
              "[class*=redrChk]",
              function (e) {
                const idRs = e.currentTarget.attributes[1].value;
                const rsCheck = $(`#${idRs}`).is(":checked");
                redRust(e.currentTarget.attributes[3].value, rsCheck);
              }
            );
            $("#activeRunsDetail").on(
              "keyup",
              "[class*=whitesInp]",
              function (e) {
                const idHws = e.currentTarget.attributes[1].value; // 1 es id de html
                const hws = $(`#${idHws}`).val();
                getHoursWs(hws, e.currentTarget.attributes[3].value); // 3 es id de Part
              }
            );
            $("#activeRunsDetail").on(
              "keyup",
              "[class*=redrInp]",
              function (e) {
                const idHrss = e.currentTarget.attributes[1].value; // 1 es id de html
                const hrss = $(`#${idHrss}`).val();
                getHoursRs(hrss, e.currentTarget.attributes[3].value); // 3 es id de Part
              }
            );
          },
        });
      });
    };

    const whiteSalt = (id, wsValue) => {
      isWsRsChanges.value = true;
      const idWs = Number(id);
      const wsPos = parts.value.findIndex((el) => el.id === idWs);
      parts.value[wsPos].ws = wsValue;
      // console.log(parts);
    };

    const redRust = (id, rsValue) => {
      isWsRsChanges.value = true;
      const idRs = Number(id);
      const rsPos = parts.value.findIndex((el) => el.id === idRs);
      parts.value[rsPos].rs = rsValue;
      // console.log(parts);
    };

    const getHoursWs = (hours, id) => {
      isWsRsChanges.value = true;
      let hoursWs;
      hours !== "" ? (hoursWs = Number(hours)) : (hoursWs = null);
      // console.log(hoursWs)
      const idHWs = Number(id);
      const hwsPos = parts.value.findIndex((el) => el.id === idHWs);
      parts.value[hwsPos].hoursWs = hoursWs;
      // console.log({parts, hoursWs, idHWs})
    };

    const getHoursRs = (hours, id) => {
      isWsRsChanges.value = true;
      let hoursRs;
      hours !== "" ? (hoursRs = Number(hours)) : (hoursRs = null);
      // console.log(hoursRs);
      const idHrss = Number(id);
      const hrssPos = parts.value.findIndex((el) => el.id === idHrss);
      parts.value[hrssPos].hoursRs = hoursRs;
      // console.log({parts, hoursRs, idHrss})
    };

    const updateWsRs = async () => {
      if (runDetail.value.status === 1) {
        return;
      }
      isModalSaveWsRs.value = false;
      loading.value = true;
      isWsRsChanges.value = false;

      // console.log(parts);
      const partsUpdated = {
        runId: runDetail.value.id,
        hours: runDetail.value.hours,
        parts: {
          ...parts.value,
        },
      };

      const res = await axios.post(`/run/updatePartsWsRs`, partsUpdated);
      const { ok, value, message } = res.data;

      if (ok) {
        gettingData();
        // generateForm();
        // $("#activeRunsDetail").DataTable().clear().destroy();
        // await generateDataTableDetail();
        makeToast(message);
        loading.value = false;
        // window.location.href = `/part/${value.id}`;
        // Inertia.get(`/part/${value.id}`)
      } else {
        makeToast("An error has occurred", "error");
      }

      // aquí se llama a la API
    };

    gettingData();

    return {
      generateDataTableDetail,
      gettingData,
      runDetail,
      startDate,
      goBack,
      editRun,
      reportRun,
      openModalEmail,
      closeModalEmail,
      reportAndPhotosRun,
      modalEmail,
      emailSend,
      id,
      isModalDelete,
      isModalClose,
      isModalSaveWsRs,
      isWsRsChanges,
      closeDeleteModal,
      deleteRun,
      closeRun,
      reopenRun,
      showReOpen,
      isModalReOpen,
      closeReOpenModal: () => (isModalReOpen.value = false),
      showSaveWsRs,
      closeSaveWsRs,
      closePhotosModal,
      showPhotos,
      isModalPhotos,
      loading,
      editRun,
      showDelete,
      showClose,
      generateForm,
      updateWsRs,
      closeCloseModal: () => (isModalClose.value = false),
    };
  },
};
</script>

<style>
.whitesInp {
  border: none;
  text-align: right !important;
}
.whitesInp:focus {
  border: 1px solid gray;
}
.redrInp {
  border: none;
  text-align: right !important;
}
.redrInp:focus {
  border: 1px solid gray;
}
</style>