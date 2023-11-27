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
<v-sheet color="#FACD3F">
    <v-card-item>
      <template v-slot:prepend>
        <v-card-title>
          <v-icon
            icon="mdi-calendar"
            start
          ></v-icon>
          <!-- {{ reminderData.length !== undefined ? reminderData.length + ' Events' : 'Loading...' }} -->
          Reminders
        </v-card-title>
      </template>

      <v-divider vertical class="mx-2"></v-divider>
    </v-card-item>
</v-sheet>

<v-card color="#FFF2C6"
rounded="lg" variant="flat" class="mb-4"
>

<v-card class="mx-2 rounded-card" max-width="400"
v-for="submissionPost in reminderData" :key="submissionPost.id" :style="{ borderColor: getBorderColor(submissionPost.section) }">

  <v-row>

  <v-col cols="2">
    <!-- <v-list-item>
    <v-list-item-content>

      <v-list-item-icon> -->
        <v-avatar color="#FACD3F" class="align-self-start ml-2 ma-3" size="50">
          <v-icon :class="getIconClass(submissionPost.section)" size="20"></v-icon>
        </v-avatar>
      <!-- </v-list-item-icon> -->
    </v-col>
    <v-col cols="10">

      <v-card-title class="headline custome">{{ submissionPost.title }}</v-card-title>
      <v-card-text class="textclass" ><v-chip class ="custome-chip" :color="getChipColor(submissionPost.section)"
        style="margin-bottom: 5px; font-size: 10px; height: 20px;">
        {{ submissionPost.section }}
      </v-chip></v-card-text>
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


      <!-- <v-divider></v-divider> -->
      <v-btn
          class="ml-3 me-2 text-none addSubBut"
          color="#FACD3F"
          prepend-icon="mdi-arrow-right-bold-circle"
          variant="flat"
          style="height: 30px; margin-bottom: 10px; border-radius: 20px;">

          Add submission
        </v-btn>
    </v-col>
    <!-- </v-list-item-content>
  </v-list-item> -->
</v-row>

</v-card>
</v-card>

<!-- </v-sheet> -->
    <v-card
      class="mx-auto"
      max-width="200"
      height="200"
      image="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
      title="Card title"
      theme="dark"
    ></v-card>

    <!-- <template> -->

      <!-- NO USEEEE -->
  <!-- <v-card
    class="mx-auto mt-4"
    color="#404040"
    max-width="650"
    min-height="350"
    theme="dark"
    variant="flat"
  >
    <v-sheet color="#8AA282">
      <v-card-item>
        <template v-slot:prepend>
          <v-card-title>
            <v-icon
              icon="mdi-calendar"
              start
            ></v-icon>
            1 Event
          </v-card-title>
        </template>

        <v-divider vertical class="mx-2"></v-divider>

        <template v-slot:append>
          <v-btn
            icon="$close"
            size="large"
            variant="text"
          ></v-btn>
        </template>
      </v-card-item>
    </v-sheet>

    <v-card
      class="ma-4"
      color="#DAA4A5"
      rounded="lg"
      variant="flat"
    >
      <v-card-item>
        <v-card-title class="text-body-2 d-flex align-center">
          <v-icon
            color="#B69555"
            icon="mdi-calendar"
            start
          ></v-icon>

          <span class="text-medium-emphasis font-weight-bold">1 Fri Dec 16th - 9:00 PM</span>

          <v-spacer></v-spacer>

          <v-avatar
            image="https://cdn.vuetifyjs.com/images/john-smirk.png"
            size="x-small"
          ></v-avatar>

          <v-chip
            class="ms-2 text-medium-emphasis"
            color="grey-darken-4"
            prepend-icon="mdi-account-multiple"
            size="small"
            text="81"
            variant="flat"
          ></v-chip>
        </v-card-title>

        <div class="py-2">
          <div class="text-h6">Live Q&A</div>

          <div class="font-weight-light text-medium-emphasis">
            Join the Vuetify team for a live Question and Answer session.
          </div>
        </div>
      </v-card-item>

      <v-divider></v-divider>

      <div class="pa-4 d-flex align-center">
        <v-icon
          color="disabled"
          icon="mdi-broadcast"
          start
        ></v-icon>

        <v-icon
          color="#B69555"
          icon="mdi-video-vintage"
          size="x-small"
        ></v-icon>

        <span class="text-caption text-medium-emphasis ms-1 font-weight-light">
          streaming
        </span>

        <v-spacer></v-spacer>

        <v-btn
          icon="mdi-dots-horizontal"
          variant="text"
        ></v-btn>

        <v-btn
          class="me-2 text-none"
          color="#4f545c"
          prepend-icon="mdi-export-variant"
          variant="flat"
        >
          Share
        </v-btn>

        <v-btn
          border
          class="text-none"
          prepend-icon="mdi-check"
          variant="text"
        >
          Interested
        </v-btn>
      </div>
    </v-card>
  </v-card> -->

  <!-- NO USEEE ENDDDD -->
    <!-- </template> -->
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
                return 'red';
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
  margin:10px;
}
.v-card-text {
  white-space: normal;
  line-height: 0rem;
}

.headline {
  font-size: 14px; /* Change this to your desired font size */
}

.subheading {
  font-size: 12px; /* Change this to your desired font size */
}


.custome {
  line-height: 0rem;
  padding-bottom: 2px;
  padding-top: 2px;
}

/* Reduce the space between the elements on the right side */
/* .v-card-title,
.v-card-subtitle,
.v-card-text,*/

.textclass{
  /* Adjust the padding as needed */
  line-height: 0%;
  padding-left: 7px;
  padding-bottom: 0px;
  margin-bottom: 2px;
  margin-left: 5px;

}
.addSubBut{
  font-size: 12px;
}
</style>
