var FewThings = angular.module('FewThings',[])

.factory("playlistFactory",['$http',function($http){
    //
    var apiKey = "AIzaSyB4pINEbEV_CczgRAhMhIza1OAEzSJV6JA";
    return {
        getPlayList : function(playlistId){
            return $http.get('https://www.googleapis.com/youtube/v3/playlistItems', {
                params : {part: 'snippet', maxResults: 50, playlistId: playlistId, key:apiKey}
            });
        },
        getVideo : function(videoId){
            return $http.get('https://www.googleapis.com/youtube/v3/videos', {
                params : {part: 'statistics', id: videoId, key:apiKey}
            });
        }
    }
}])

.controller("FewThingsCtrl",['$scope','playlistFactory',function($scope,playlistFactory){
    //PLibb4vBWtNCStS53hQhpMLaG9ry-ykrVp
    playlistFactory.getPlayList('PLibb4vBWtNCStS53hQhpMLaG9ry-ykrVp').then(function (response) {
        $scope.playlistsItems = response.data.items;
        angular.forEach($scope.playlistsItems,function(value,key){
            playlistFactory.getVideo(value.snippet.resourceId.videoId).then(function(data){
                value.snippet.viewCount = data.data.items[0].statistics.viewCount;
            });
        });
    });
}])

.directive('dotdotdot', ['$timeout', function($timeout) {
        return {
            restrict: 'A',
            link: function(scope, element, attributes) {

                scope.$watch(attributes.dotdotdot, function() {

                    $timeout(function() {
                        element.dotdotdot();
                    }, 400);
                });
            }
        }
}]);
