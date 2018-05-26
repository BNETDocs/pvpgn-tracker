#tag Class
Protected Class TrackerMessage
	#tag Method, Flags = &h0
		Sub Constructor(SerializedData As String)
		  
		  // (UINT16)      Packet Version
		  // (UINT16)      Server Port
		  // (UINT32)      Flags
		  //  (UINT8) [32] Software
		  //  (UINT8) [16] Version
		  //  (UINT8) [32] Platform
		  //  (UINT8) [64] Server Description
		  //  (UINT8) [64] Server Location
		  //  (UINT8) [96] Server URL
		  //  (UINT8) [64] Contact Name
		  //  (UINT8) [64] Contact Email
		  // (UINT32)      Active Users
		  // (UINT32)      Active Channels
		  // (UINT32)      Active Games
		  // (UINT32)      Uptime
		  // (UINT32)      Total Games
		  // (UINT32)      Total Logins
		  
		  If LenB( SerializedData ) <> Me.Size Then
		    Dim err As New OutOfBoundsException()
		    Raise err
		  End If
		  
		  Me.rawData = SerializedData
		  
		End Sub
	#tag EndMethod

	#tag Method, Flags = &h0
		Function Operator_Convert() As String
		  
		  Return Me.rawData
		  
		End Function
	#tag EndMethod


	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt32Value( 444 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt32Value( 444 ) = value
			  
			End Set
		#tag EndSetter
		ActiveChannels As UInt32
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt32Value( 448 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt32Value( 448 ) = value
			  
			End Set
		#tag EndSetter
		ActiveGames As UInt32
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt32Value( 440 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt32Value( 440 ) = value
			  
			End Set
		#tag EndSetter
		ActiveUsers As UInt32
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 376, 64 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 376, 64 ) = value
			  
			End Set
		#tag EndSetter
		ContactEmail As CString
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 312, 64 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 312, 64 ) = value
			  
			End Set
		#tag EndSetter
		ContactName As CString
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt32Value( 4 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt32Value( 4 ) = value
			  
			End Set
		#tag EndSetter
		Flags As UInt32
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt16Value( 0 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt16Value( 0 ) = value
			  
			End Set
		#tag EndSetter
		PacketVersion As UInt16
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 56, 32 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 56, 32 ) = value
			  
			End Set
		#tag EndSetter
		Platform As CString
	#tag EndComputedProperty

	#tag Property, Flags = &h1
		Protected rawData As MemoryBlock
	#tag EndProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 88, 64 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 88, 64 ) = value
			  
			End Set
		#tag EndSetter
		ServerDescription As CString
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 152, 64 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 152, 64 ) = value
			  
			End Set
		#tag EndSetter
		ServerLocation As CString
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt16Value( 2 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt16Value( 2 ) = value
			  
			End Set
		#tag EndSetter
		ServerPort As UInt16
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 216, 96 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 216, 96 ) = value
			  
			End Set
		#tag EndSetter
		ServerURL As CString
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 8, 32 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 8, 32 ) = value
			  
			End Set
		#tag EndSetter
		Software As CString
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt32Value( 456 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt32Value( 456 ) = value
			  
			End Set
		#tag EndSetter
		TotalGames As UInt32
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt32Value( 460 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt32Value( 460 ) = value
			  
			End Set
		#tag EndSetter
		TotalLogins As UInt32
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.UInt32Value( 452 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.UInt32Value( 452 ) = value
			  
			End Set
		#tag EndSetter
		Uptime As UInt32
	#tag EndComputedProperty

	#tag ComputedProperty, Flags = &h0
		#tag Getter
			Get
			  
			  Return Me.rawData.StringValue( 40, 16 )
			  
			End Get
		#tag EndGetter
		#tag Setter
			Set
			  
			  Me.rawData.StringValue( 40, 16 ) = value
			  
			End Set
		#tag EndSetter
		Version As CString
	#tag EndComputedProperty


	#tag Constant, Name = Size, Type = Double, Dynamic = False, Default = \"464", Scope = Public
	#tag EndConstant

	#tag Constant, Name = TF_PRIVATE, Type = Double, Dynamic = False, Default = \"&H00000002", Scope = Public
	#tag EndConstant

	#tag Constant, Name = TF_SHUTDOWN, Type = Double, Dynamic = False, Default = \"&H00000001", Scope = Public
	#tag EndConstant


	#tag ViewBehavior
		#tag ViewProperty
			Name="ContactEmail"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
		#tag ViewProperty
			Name="ContactName"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Index"
			Visible=true
			Group="ID"
			InitialValue="-2147483648"
			InheritedFrom="Object"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Left"
			Visible=true
			Group="Position"
			InitialValue="0"
			InheritedFrom="Object"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Name"
			Visible=true
			Group="ID"
			InheritedFrom="Object"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Platform"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
		#tag ViewProperty
			Name="ServerDescription"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
		#tag ViewProperty
			Name="ServerLocation"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
		#tag ViewProperty
			Name="ServerURL"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Software"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Super"
			Visible=true
			Group="ID"
			InheritedFrom="Object"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Top"
			Visible=true
			Group="Position"
			InitialValue="0"
			InheritedFrom="Object"
		#tag EndViewProperty
		#tag ViewProperty
			Name="Version"
			Group="Behavior"
			Type="String"
		#tag EndViewProperty
	#tag EndViewBehavior
End Class
#tag EndClass
