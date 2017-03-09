/**
 * Created by jbarron on 3/9/17.
 * Jesus Arriaga Barron - 2017
 * jarriagabarron@gmail.com
 *
 */
var endpoint = "http://localhost:8000";

//Define the model for a task
function Task(data){
    this.id = ko.observable(data.id);
    this.description = ko.observable(data.description);
    this.position = ko.observable(data.position);
  //show and hide the input and span
    this.enableEdition = ko.observable(false);
}

//main function
function TodosListViewModel(){

  var self = this;
  //define the array of task observable
  self.tasks = ko.observableArray([]);


//handle show input and span, pass the task in mouseover status
  self.activeEdition = function(task){
    task.enableEdition(true);
  };
//handle show input and span , pass the task in mouseout status
  self.deactivateEdition = function(task){
    task.enableEdition(false);
  };

//function to push changes in a task
  self.updateTasks = function(task){
    $.ajax(endpoint+"/api/task/"+task.id(), {
      data: ko.toJSON({ description: task.description }),
      type: "patch", contentType: "application/json",
      success: function(result) { console.log(result) }
    });
  };

//function to delete a task
  self.deleteTask = function(task){
    $.ajax(endpoint+"/api/task/"+task.id(),{
      type:"delete",
      success:function(result){console.log(result); self.getAll(); }
    });
  }

  //function to push a new task
  self.postTask = function(){
    $.ajax(endpoint+"/api/task",{
      type:"post",
      success:function(result){console.log(result);self.getAll();}
    });
  };

  //call the api tasks
  self.getAll = function(){
    $.getJSON(endpoint+"/api/tasks",function(response){
      var mappedTasks = $.map(response,function(taskData){return new Task(taskData);});
      self.tasks(mappedTasks);
    });
  };

  self.sort =function(sortedIDs){
    $.ajax(endpoint+'/api/task/sort',{
      type:"post",
      data:{sort:sortedIDs},
      success:function(result){console.log(result);self.getAll();}
    });
  }
  //initialize the tasks from API
  self.getAll();
  //listen for update sort
  $( "#sortable" ).sortable({
    update: function( event, ui ) {
      var sortedIDs = $( "#sortable" ).sortable( "toArray" );
     self.sort(sortedIDs);
    }
  });

};


ko.applyBindings(new TodosListViewModel());


//sortable functions using Jquery UI
jQuery(document).ready(function(){
  $( "#sortable" ).sortable();
  $( "#sortable" ).disableSelection();
});