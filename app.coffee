# Thanks for your help with base64 stuff Denny!
angular.module 'helpscout_web_app', []
	.controller 'main', ['$scope','$http', '$interval', ($scope,$http,$interval) ->
		
		$scope.api_key = null

		#I'll go with the simple object for now.
		$scope.mailboxes = {}
		
		$http.get('/api_key.php').success (data) ->
			$scope.api_key = data
			
			if data isnt false
				$scope.hide_it = true
				$scope.init()

		#Dunno why this request is so weird.
		api_auth = ->
			return 'Basic ' + Base64.encode $scope.api_key + ':X'
		
		request_headers = ->
			return {headers: {'Authorization':api_auth(), 'Content-Type':'application/x-www-form-urlencoded'}}

		$scope.init = ->
			$http.get('https://api.helpscout.net/v1/mailboxes.json', {headers: {'Authorization':api_auth(), 'Content-Type':'application/x-www-form-urlencoded'}}).success($scope.mailbox_controller)
		
		$scope.mailbox_controller = (data) ->
			$scope.api_ready = true;

			for item in data.items

				#Just want to make sure that the id is a string.
				mailbox_id = String(data.items[i].id);

				#I'll make this prettier later.
				$scope.mailboxes[mailbox_id] = {}
				$scope.mailboxes[mailbox_id].id = mailbox_id
				$scope.mailboxes[mailbox_id].name = item.name
				$scope.mailboxes[mailbox_id].email = item.email
				$scope.mailboxes[mailbox_id].active_tickets = null
				$scope.mailboxes[mailbox_id].pending_tickets = null
				$scope.mailboxes[mailbox_id].folders = []
				#Takes the argument "active" or "total"
				$scope.mailboxes[mailbox_id].get_count = (status, obj) ->
					
					#Couldn't figure out how to implement "this" here, hence the
					#extra argument, but let's roll with it.

					count = null

					for folder in obj.folders
						if folder.type is 'mytickets' or folder.type is 'open'
							count = count + folder[status+'Count']

					return count

				#The way I've set this up, the only argument I will need to ever
				#pass is the mailbox_id.
				$scope.add_mailbox_listener mailbox_id

		
		$scope.add_mailbox_listener = (mailbox_id) ->
			success_callback = (data) ->
				#Reset the color.
				$scope.mailboxes[mailbox_id].color = 'white';


				#Update the numbers from last time.
				$scope.mailboxes[mailbox_id].active_tickets_last = $scope.mailboxes[mailbox_id].active_tickets;
				$scope.mailboxes[mailbox_id].pending_tickets_last = $scope.mailboxes[mailbox_id].pending_tickets;

				for folder, idx in data.item.folders
					$scope.mailboxes[mailbox_id].folders[idx] = folder

				$scope.mailboxes[mailbox_id].active_tickets = $scope.mailboxes[mailbox_id].get_count 'active', $scope.mailboxes[mailbox_id]
				$scope.mailboxes[mailbox_id].total_tickets = $scope.mailboxes[mailbox_id].get_count 'total', $scope.mailboxes[mailbox_id]

				#CSS animations!.
				if $scope.mailboxes[mailbox_id].active_tickets > $scope.mailboxes[mailbox_id].active_tickets_last
					$scope.mailboxes[mailbox_id].color = 'lightseagreen'
				else if $scope.mailboxes[mailbox_id].active_tickets < $scope.mailboxes[mailbox_id].active_tickets_last
					$scope.mailboxes[mailbox_id].color = 'lightgreen'


			#We'll start the update rate off at 2000
			$interval ->
				$http.get 'https://api.helpscout.net/v1/mailboxes/' + mailbox_id + '.json' , request_headers()
					.success success_callback
			, 2000

]

angular.element(document).ready ->
	angular.bootstrap document, ['helpscout_web_app']