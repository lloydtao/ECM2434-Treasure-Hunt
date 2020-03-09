var photoSubmit = new Vue({
        el: '#objectives',
        data: {
            objectives: {},
            showUpload: false,
            currentObjective: null,
            imgPath: null
        },
        methods: {
            /**
             * Gets photo objectives from JSON and their descriptions from DB
             */
            getObjectives() {
                //@TODO change hardcoded data
                var gamePin = 'NOU1'; //hardcoded for now
                var teamName = "yeet"; //hardcoded for now

                //get Json data
                fetch("hunt_sessions/" + gamePin + ".json")
                    .then(response => response.text())
                    .then((response) => {
                        var json = response;
                        if (json !== '') {
                            var huntSessionData = JSON.parse(json);
                        }
                        //save objective data to vue component
                        this.objectives = huntSessionData["teams"][teamName]["objectives"]['photo'];
                        var objectiveIDs = [];
                        //create array of objective IDs
                        for (var objective in this.objectives) {
                            if (this.objectives.hasOwnProperty(objective)) {
                                objectiveIDs.push(this.objectives[objective]["objectiveId"]);
                            }
                        }
                        //get objective descriptions from DB
                        fetch("api/objectivedescription.php?objectiveIDs=" + objectiveIDs)
                            .then(response => response.json())
                            .then(response => {
                                let index = 0;
                                //add objective descriptions to vue component
                                for (let objective in this.objectives) {
                                    if (this.objectives.hasOwnProperty(objective)) {
                                        Vue.set(this.objectives[objective], "description", response[index]);
                                    }
                                    index++;
                                }
                            });
                    });
            },
            submitForm() {
                $(function () {
                    var formData = new FormData($('#uploadForm')[0]);
                    formData.append("objective_id", photoSubmit.currentObjective);
                    $.ajax({
                        type: "POST",
                        url: "/api/upload_photo.php",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            response = $.parseJSON(response);
                            if (response['status'] === 'ok') {
                                photoSubmit.showUpload = false;
                                photoSubmit.currentObjective = null;
                                photoSubmit.getObjectives();
                            } else if (response['status'] === 'error' ) {
                                alert(response['message']);
                                //@TODO consider using custom error box
                            }
                        },
                        error: function (response) {
                            console.log(response);
                        }

                    });
                });

                return false;
            },
            showUploadForm(index) {
                this.currentObjective = index;
                //used to prevent image caching
                var date = new Date;
                this.imgPath = this.objectives[this.currentObjective]['path'] + "?" + date.getSeconds();
                this.showUpload = true;
            },
            hideUploadForm(){
                this.currentObjective = null;
                this.showUpload = false;
            }
        },
        mounted() {
            this.getObjectives();
            currentObjective = null;
        }
    });