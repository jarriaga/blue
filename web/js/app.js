/**
 * Created by jbarron on 3/9/17.
 */

//Define the model for a task
function Task(data){
    this.id = ko.observable(data.id);
    this.description = ko.observable(data.description);
    this.position = ko.observable(data.position);
  //show and hide the input and span
    this.enableEdition = ko.observable(false);
    this.showDescription = ko.observable(true);
}

//main function
function TodosListViewModel(){

  var self = this;
  //define the array of task observable
  self.tasks = ko.observableArray([]);


//handle show input and span, pass the task in mouseover status
  self.activeEdition = function(task){
    task.enableEdition(true);
    task.showDescription(false);
  };
//handle show input and span , pass the task in mouseout status
  self.deactivateEdition = function(task){
    task.enableEdition(false);
    task.showDescription(true);
  };

  //call the api tasks
  $.getJSON("/api/tasks",function(response){
    var mappedTasks = $.map(response,function(taskData){return new Task(taskData);});
    self.tasks(mappedTasks);
  });

};


ko.applyBindings(new TodosListViewModel());

//sortable functions using Jquery UI
jQuery(document).ready(function(){
  $( "#sortable" ).sortable();
  $( "#sortable" ).disableSelection();
});