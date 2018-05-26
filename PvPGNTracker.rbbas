#tag Class
Protected Class PvPGNTracker
Inherits UDPListener
	#tag Event
		Sub PacketAvailable(Data As Datagram)
		  
		  If Data = Nil Then
		    If Me.Verbose Then stderr.WriteLine( "Received null Datagram in PacketAvailable" )
		    Return
		  End If
		  
		  If LenB( Data.Data ) <> TrackerMessage.Size Then
		    stdout.WriteLine( "Received invalid solicit from [" + Data.Address + ":" + Format( Data.Port, "-#" ) + "]" )
		    Return
		  End If
		  
		  Dim msg As New TrackerMessage( Data.Data )
		  
		  stdout.WriteLine( "<-- [" + Data.Address + ":" + Format( Data.Port, "-#" ) + "] Received solicit request" )
		  
		  If msg.PacketVersion <> &H0002 Then
		    stdout.WriteLine( "   unknown version: " + Format( msg.PacketVersion, "-#" ))
		    Return
		  End If
		  
		  stdout.WriteLine( "      PvPGN Port: " + Format( msg.ServerPort, "-#" ))
		  stdout.Write( "           Flags: " )
		  
		  If msg.Flags = ( TrackerMessage.TF_SHUTDOWN Or TrackerMessage.TF_PRIVATE ) Then
		    stdout.WriteLine( "Private, Shutdown" )
		  ElseIf msg.Flags = TrackerMessage.TF_SHUTDOWN Then
		    stdout.WriteLine( "Shutdown" )
		  ElseIf msg.Flags = TrackerMessage.TF_PRIVATE Then
		    stdout.WriteLine( "Private" )
		  ElseIf msg.Flags = 0 Then
		    stdout.WriteLine( "None" )
		  Else
		    stdout.WriteLine( "0x" + Right( "0000000" + Hex( msg.Flags ), 8 ))
		  End If
		  
		  stdout.WriteLine( "        Software: " + msg.Software )
		  stdout.WriteLine( "         Version: " + msg.Version )
		  stdout.WriteLine( "        Platform: " + msg.Platform )
		  stdout.WriteLine( "    PvPGN Descr.: " + msg.ServerDescription )
		  stdout.WriteLine( "      PvPGN Loc.: " + msg.ServerLocation )
		  stdout.WriteLine( "   Community URL: " + msg.ServerURL )
		  stdout.WriteLine( "    Contact Name: " + msg.ContactName )
		  stdout.WriteLine( "   Contact Email: " + msg.ContactEmail )
		  stdout.WriteLine( "    Active Users: " + Format( msg.ActiveUsers, "-#" ))
		  stdout.WriteLine( " Active Channels: " + Format( msg.ActiveChannels, "-#" ))
		  stdout.WriteLine( "    Active Games: " + Format( msg.ActiveGames, "-#" ))
		  stdout.WriteLine( "     Total Games: " + Format( msg.TotalGames, "-#" ))
		  stdout.WriteLine( "    Total Logins: " + Format( msg.TotalLogins, "-#" ))
		  stdout.WriteLine( "          Uptime: " + App.TimeString( msg.Uptime ) + " (" + Format( msg.Uptime, "-#" ) + ")" )
		  stdout.WriteLine( " -- End -- " )
		  
		  App.TrackAPI.Solicit( msg )
		  
		End Sub
	#tag EndEvent


	#tag Method, Flags = &h1000
		Sub Constructor()
		  // Calling the overridden superclass constructor.
		  // Note that this may need modifications if there are multiple constructor choices.
		  // Possible constructor calls:
		  // Constructor() -- From UDPSocket
		  // Constructor() -- From SocketCore
		  Super.Constructor()
		  
		  Me.Port = 6114
		  Me.RouterHops = 30
		  Me.Verbose = False
		  
		End Sub
	#tag EndMethod


	#tag ViewBehavior
		#tag ViewProperty
			Name="Index"
			Visible=true
			Group="ID"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Left"
			Visible=true
			Group="Position"
			Type="Integer"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Name"
			Visible=true
			Group="ID"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Port"
			Visible=true
			Group="Behavior"
			InitialValue="0"
			Type="Integer"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="RouterHops"
			Visible=true
			Group="Behavior"
			InitialValue="32"
			Type="Integer"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="SendToSelf"
			Visible=true
			Group="Behavior"
			InitialValue="False"
			Type="Boolean"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Super"
			Visible=true
			Group="ID"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Top"
			Visible=true
			Group="Position"
			Type="Integer"
			InheritedFrom="UDPSocket"
		#tag EndViewProperty
	#tag EndViewBehavior
End Class
#tag EndClass
