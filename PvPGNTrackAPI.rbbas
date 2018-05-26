#tag Class
Protected Class PvPGNTrackAPI
Inherits HTTPSecureSocket
	#tag Method, Flags = &h1000
		Sub Constructor()
		  // Calling the overridden superclass constructor.
		  // Note that this may need modifications if there are multiple constructor choices.
		  // Possible constructor calls:
		  // Constructor() -- From HTTPSecureSocket
		  // Constructor() -- From TCPSocket
		  // Constructor() -- From SocketCore
		  Super.Constructor()
		  
		  Me.ConnectionType = Me.TLSv1
		  Me.DefaultPort = 443
		  Me.Secure = True
		  Me.Yield = True
		  
		End Sub
	#tag EndMethod

	#tag Method, Flags = &h0
		Sub Solicit(Message As TrackerMessage)
		  
		  Dim messageTree As New JSONItem()
		  
		  // JSONItem cannot represent CString types, so we convert them to String.
		  
		  Dim n_software As String = Message.Software
		  Dim n_version As String = Message.Version
		  Dim n_platform As String = Message.Platform
		  Dim n_serverDescription As String = Message.ServerDescription
		  Dim n_serverLocation As String = Message.ServerLocation
		  Dim n_serverURL As String = Message.ServerURL
		  Dim n_contactName As String = Message.ContactName
		  Dim n_contactEmail As String = Message.ContactEmail
		  
		  messageTree.Value( "server_port" ) = Message.ServerPort
		  messageTree.Value( "flags" ) = Message.Flags
		  messageTree.Value( "software" ) = n_software
		  messageTree.Value( "version" ) = n_version
		  messageTree.Value( "platform" ) = n_platform
		  messageTree.Value( "server_description" ) = n_serverDescription
		  messageTree.Value( "server_location" ) = n_serverLocation
		  messageTree.Value( "server_url" ) = n_serverURL
		  messageTree.Value( "contact_name" ) = n_contactName
		  messageTree.Value( "contact_email" ) = n_contactEmail
		  messageTree.Value( "active_users" ) = Message.ActiveUsers
		  messageTree.Value( "active_channels" ) = Message.ActiveChannels
		  messageTree.Value( "active_games" ) = Message.ActiveGames
		  messageTree.Value( "total_games" ) = Message.TotalGames
		  messageTree.Value( "total_logins" ) = Message.TotalLogins
		  messageTree.Value( "uptime" ) = Message.Uptime
		  
		  Dim form As New JSONItem()
		  
		  form.Child( "message" ) = messageTree
		  
		  Dim formString As String = form.ToString()
		  
		  Me.ClearRequestHeaders()
		  
		  Me.requestHeaders.SetHeader( "User-Agent", "PvPGNTrack/" + Format( App.MajorVersion, "-#" ) + "." + Format( App.MinorVersion, "-#" ))
		  
		  Me.SetRequestContent( formString, "application/json;charset=utf-8" )
		  
		  If Me.Verbose Then
		    stderr.WriteLine( "Sending solicit request to api..." )
		  End If
		  
		  Me.SendRequest( "POST", "http://utility:6114/solicit" )
		  
		End Sub
	#tag EndMethod


	#tag Property, Flags = &h0
		Verbose As Boolean
	#tag EndProperty


	#tag ViewBehavior
		#tag ViewProperty
			Name="CertificateFile"
			Visible=true
			Group="Behavior"
			Type="FolderItem"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="CertificatePassword"
			Visible=true
			Group="Behavior"
			Type="String"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="CertificateRejectionFile"
			Visible=true
			Group="Behavior"
			Type="FolderItem"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="ConnectionType"
			Visible=true
			Group="Behavior"
			InitialValue="2"
			Type="Integer"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Index"
			Visible=true
			Group="ID"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Left"
			Visible=true
			Group="Position"
			Type="Integer"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Name"
			Visible=true
			Group="ID"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Secure"
			Visible=true
			Group="Behavior"
			Type="Boolean"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Super"
			Visible=true
			Group="ID"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Top"
			Visible=true
			Group="Position"
			Type="Integer"
			InheritedFrom="HTTPSecureSocket"
		#tag EndViewProperty
	#tag EndViewBehavior
End Class
#tag EndClass
