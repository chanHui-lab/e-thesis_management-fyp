<template v-slot:append>
  <!-- <ul>
    <li v-for="(value, key) in reminderData" :key="key">
      {{ key }}: {{ value }}
    </li>
  </ul> -->


<!-- <v-row align="center" justify="center">
  <v-col cols="auto">
      <v-card
        class="mx-auto"
        max-width="344"
        title="Icons"
        subtitle="prepend-icon and append-icon"
        prepend-icon="mdi-account"
        append-icon="mdi-check"
      >
        <v-card-text>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</v-card-text>
      </v-card>
    </v-col>
</v-row> -->
<!-- <ul>
  <li v-for="(value, key) in reminderData" :key="key">
    {{ key }}: {{ value }}
  </li>
</ul> -->

<!-- <div style="display: flex;"> -->
<!-- <v-row align="center" justify="center" style="display: flex;"> -->
    <!-- <v-col cols="12" v-for="submissionPost in reminderData" :key="submissionPost.id" md="6" lg="4"> -->

      <div class = "rounded card">

<v-sheet color="#FFF2C6">
    <v-card-item>
      <template v-slot:prepend>
        <v-card-title style="font-size: 24px;">
          <v-icon
            icon="mdi-bell"
            start
            size="24"
          ></v-icon>
          <!-- {{ reminderData.length !== undefined ? reminderData.length + ' Events' : 'Loading...' }} -->
          Upcoming Events
        </v-card-title>
      </template>

      <v-divider vertical class="mx-2"></v-divider>
    </v-card-item>
</v-sheet>

<v-card color="#ffffff"
rounded="lg" variant="flat" class="mb-4"
>

<v-card class="mx-4 rounded-card" max-width="600"
v-for="submissionPost in reminderData" :key="submissionPost.id"
:style="{ borderColor: getBorderColor(submissionPost.section) }">

  <v-row>

  <v-col cols="2" style="padding-right:10px;">
    <!-- <v-list-item>
    <v-list-item-content>

      <v-list-item-icon> -->
        <v-avatar color="#FACD3F" class="align-self-start ml-2 ma-3" size="40">
          <v-icon :class="getIconClass(submissionPost.section)" size="20"></v-icon>
        </v-avatar>
      <!-- </v-list-item-icon> -->
    </v-col>
    <v-col cols="10" style="display: flex; flex-direction: column; max-width: 80%;">

      <v-card-title class="headline custome" style="word-break: break-all; max-width: 100%;">{{ submissionPost.title }}</v-card-title>
      <v-card-text class="textclass" >
        <v-chip class ="custome-chip" :color="getChipColor(submissionPost.section)"
          style="margin-bottom: 5px; font-size: 10px; height: 20px;">
          {{ submissionPost.section }}
        </v-chip>
      </v-card-text>
      <v-card-text class="subheading custome" style="color: grey;"><v-icon start icon="mdi-calendar" color="#8C6355"></v-icon>{{ submissionPost.formatted_deadline }}</v-card-text>

      <!-- Display remaining time -->
      <v-card-subtitle class="subheading custome" v-if="submissionPost.is_today_deadline">
        Deadline is today! Hurry up!
      </v-card-subtitle>

      <v-card-text style="margin-bottom: 5px;" class="subheading custome" v-else>
        Time remaining:
        {{ getTimeRemaining(submissionPost.remainingDays, submissionPost.remainingHours, submissionPost.remaining_minutes) }}
        <!-- Time remaining: {{ submissionPost.remainingDays }} days,
        {{ submissionPost.remainingHours }} hours,
        {{ submissionPost.remaining_minutes }} minutes -->
      </v-card-text>
    </v-col>
</v-row>

</v-card>
</v-card>

  </div>

</template>

<script type="module">
  // import { ref, onMounted  } from 'vue'
  import axios from 'axios';

  export default {
    data() {
      return {
        reminderData: [],
      };
    },
    created() {
      // Fetch data using a web route
      this.fetchReminderData();
    },
    computed: {
    filteredData() {
      const twoWeeksFromNow = new Date();
      console.log(twoWeeksFromNow);
      twoWeeksFromNow.setDate(twoWeeksFromNow.getDate() + 14);

      return this.reminderData.filter(submissionPost => new Date(submissionPost.submission_deadline) <= twoWeeksFromNow);
    // up there can cnaeg to in filteredDta
    },
    },
    methods: {
      getTimeRemaining(remainingDays, remainingHours, remainingMinutes) {
      const parts = [];

      if (remainingDays > 0) {
        parts.push(`${remainingDays} days`);
      }

      if (remainingHours > 0) {
        parts.push(`${remainingHours} hours`);
      }

      if (remainingMinutes > 0) {
        parts.push(`${remainingMinutes} minutes`);
      }

      return parts.join(', ');
    },
    fetchReminderData() {
      // Make an AJAX request to your Laravel web route
      axios.get('/api/student/dashreminder')
        .then(response => {
          this.reminderData = response.data;
          console.log(response.data);
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    },

    getChipColor(type) {
        switch (type) {
            case 'form':
                return 'brown';
            case 'thesis':
                return 'blue';
            case 'proposal':
                return 'green';
            case 'slides':
                return 'orange';
            default:
                return 'grey';
        }
    },
    getBorderColor(type) {
      // Return a border color based on the type (customize as needed)
      return type === 'form' ?
      'red' : type === 'thesis' ? 'blue' :
      'grey';
    },
    getIconClass(type) {
      // Return an icon class based on the type
      return type === 'form' ? 'fas fa-laptop-file'
      : type === 'thesis' ? 'fa-solid fa-file-import'
      : 'fa-solid fa-file-import';
    },
    getSubmissionRouteUrl() {
      // Replace 'your.route.name' with the actual name of your route in web.php
      // return this.$route('stutemplate.index');
      return '/student/form/submission';

    },
    },
  }

//   const data = ref([]);
//   const previewFileUrl = ref(null);  // Add this line

//   const fetchData = () => {
//   axios.get("/api/testvuedata")
//     .then((response) => {
//       console.log(response.data);
//       data.value = response.data;
//     })
//     .catch(error => {
//       console.error('Error fetching data:', error);
//     });
//   };

// const previewFile = (formFile) => {
//   const fileObject = JSON.parse(formFile);
//   if (Array.isArray(fileObject) && fileObject.length > 0) {
//     const filePath = fileObject[0].path;
//     console.log('File Path:', filePath);

//   // Assuming file is a PDF and its path is stored in file.path
//     // previewFileUrl.value = `https://mozilla.github.io/pdf.js/web/viewer.html?file=${(filePath)}`;

//     // doesnt work yet....
//     previewFileUrl.value = `https://mozilla.github.io/pdf.js/web/viewer.html?file=${window.location.origin}/storage/${filePath}`;

//     // Assuming doc is a docx
//     // previewFileUrl.value = `https://docs.google.com/viewer?url=${(filePath)}&embedded=true`;

//   }
// };

// const downloadFile = (formFile) => {
//   console.log(formFile);
//   const fileObject = JSON.parse(formFile);
//   console.log('fileObject:', fileObject);

//   if (Array.isArray(fileObject) && fileObject.length > 0) {
//     const filePath = fileObject[0].path;
//     console.log('File Path:', filePath);
//     console.log(encodeURIComponent(filePath));
//     window.location.href = `api/download/${(filePath)}`;
//   } else {
//     console.error('Form file is undefined or empty.');
//   }
// };

</script>

<style>
.rounded-card {
  border-radius: 20px;
  border-left: 5px solid #FACD3F;
  margin:20px;
}
.v-card-text {
  white-space: normal;
  line-height: 0rem;
}

.headline {
  font-size: 16px; /* Change this to your desired font size */
}

.subheading {
  font-size: 14px; /* Change this to your desired font size */
}


.custome {
  line-height: 0rem;
  padding-bottom: 2px;
  padding-top: 4px;
}

.custome-chip{
  margin-left: 0px;
}

.textclass{
  /* Adjust the padding as needed */
  line-height: 0%;
  padding-left: 9px;
  padding-bottom: 0px;
  margin-bottom: 2px;
  /* margin-left: 5px; */
}

.addSubBut{
  font-size: 12px;
}

</style>
