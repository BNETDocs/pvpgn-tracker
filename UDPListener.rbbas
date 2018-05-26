#tag Class
Protected Class UDPListener
Inherits UDPSocket
	#tag Event
		Sub DataAvailable()
		  
		  Dim gram As Datagram
		  
		  While Me.PacketsAvailable > 0
		    
		    gram = Me.Read()
		    
		    If Me.Verbose Then
		      stderr.WriteLine( "<-- [" + gram.Address + ":" + Format( gram.Port, "-#" ) + "] " + App.ToHexString( gram.Data, " " ))
		    End If
		    
		    PacketAvailable( gram )
		    
		  Wend
		  
		End Sub
	#tag EndEvent

	#tag Event
		Sub Error()
		  
		  If Me.Verbose Then stderr.WriteLine( "Socket error " + Format( Me.LastErrorCode, "-#" ))
		  
		  Error()
		  
		End Sub
	#tag EndEvent


	#tag Method, Flags = &h0
		Sub Close()
		  
		  If Me.Verbose And Me.IsConnected Then
		    stderr.WriteLine( "Unbinding..." )
		  End If
		  
		  Super.Close()
		  
		End Sub
	#tag EndMethod

	#tag Method, Flags = &h0
		Sub Connect()
		  
		  If Me.Verbose Then stderr.Write( "Binding listener to " + Format( Me.Port, "-#" ) + "/udp... " )
		  
		  Super.Connect()
		  
		  If Me.Verbose Then
		    If Me.IsConnected Then
		      stderr.WriteLine( "success" )
		    Else
		      stderr.WriteLine( "failure [" + Format( Me.LastErrorCode, "-#" ) + "]" )
		    End If
		  End If
		  
		End Sub
	#tag EndMethod


	#tag Hook, Flags = &h0
		Event Error()
	#tag EndHook

	#tag Hook, Flags = &h0
		Event PacketAvailable(Data As Datagram)
	#tag EndHook


	#tag Property, Flags = &h0
		Verbose As Boolean
	#tag EndProperty


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
