#tag Class
Protected Class App
Inherits ConsoleApplication
	#tag Event
		Function Run(args() as String) As Integer
		  
		  stdout.WriteLine( Me.ProjectName() + " v" + Me.VersionString() )
		  
		  Me.Tracker = New PvPGNTracker()
		  Me.TrackAPI = New PvPGNTrackAPI()
		  
		  Me.HandleArgs( args )
		  
		  Me.Tracker.Connect()
		  
		  Do Until Me.ExitLoop
		    Me.DoEvents( 1 )
		    Me.YieldToNextThread()
		  Loop
		  
		  Return 0
		  
		End Function
	#tag EndEvent


	#tag Method, Flags = &h0
		Function GetNetworkInterface(value As String) As NetworkInterface
		  
		  If value = "0.0.0.0" Or value = "::" Then Return Nil
		  
		  Dim i, count As Integer
		  Dim nic As NetworkInterface
		  
		  i = 0
		  count = System.NetworkInterfaceCount
		  
		  While i <= count
		    nic = System.GetNetworkInterface( i )
		    
		    If nic.IPAddress = value Then Return nic
		    
		    i = i + 1
		  Wend
		  
		  Return Nil
		  
		End Function
	#tag EndMethod

	#tag Method, Flags = &h1
		Protected Sub HandleArgs(args() As String)
		  
		  Dim i As Integer = 1
		  Dim argCount As Integer = UBound( args )
		  
		  Dim argName As String
		  Dim argValue As String
		  Dim argSwitch As Boolean
		  
		  While i <= argCount
		    
		    argName = NthField( args( i ), "=", 1 )
		    argValue = Mid( args( i ), Len( argName ) + 2 )
		    
		    If Left( argName, 1 ) = "/" Or Left( argName, 1 ) = "-" Then
		      argName = Mid( argName, 2 )
		      argSwitch = True
		    Else
		      argSwitch = False
		    End If
		    
		    If argSwitch And InStr( argName, "=" ) = 0 And i + 1 <= argCount And _
		      Left( args( i + 1 ), 1 ) <> "/" And Left( args( i + 1 ), 1 ) <> "-" Then
		      i = i + 1
		      argValue = args( i )
		    End If
		    
		    If Not argSwitch Then
		      stderr.WriteLine( "Unknown parameter [" + argName + "]" )
		      i = i + 1
		      Continue While
		    End If
		    
		    Select Case argName
		      
		    Case "a", "api"
		      Me.TrackAPI.Endpoint = argValue
		      
		    Case "h", "help", "?"
		      stdout.WriteLine( "Usage: " + args( 0 ) + " [args]" )
		      stdout.WriteLine( "" )
		      stdout.WriteLine( "-a, -api            Set the API endpoint to forward to.")
		      stdout.WriteLine( "-h, -help, -?       Show this menu." )
		      stdout.WriteLine( "-i, -if, -interface Set the interface to bind to." )
		      stdout.WriteLine( "-p, -port           Set the udp port to bind to." )
		      stdout.WriteLine( "-v, -verbose        Set verbose mode." )
		      
		    Case "i", "if", "interface"
		      Me.Tracker.NetworkInterface = App.GetNetworkInterface( argValue )
		      
		    Case "p", "port"
		      Me.Tracker.Port = Val( argValue )
		      
		    Case "v", "verbose"
		      If Len( argValue ) = 0 Or argValue = "1" Or argValue = "on" Or argValue = "y" Or argValue = "yes" Then
		        Me.Tracker.Verbose = True
		        Me.TrackAPI.Verbose = True
		      Else
		        Me.Tracker.Verbose = False
		        Me.TrackAPI.Verbose = False
		      End If
		      
		    End Select
		    
		    i = i + 1
		  Wend
		  
		End Sub
	#tag EndMethod

	#tag Method, Flags = &h0
		Function PlatformName() As String
		  
		  #If TargetWin32 Then
		    Return "Windows"
		  #ElseIf TargetLinux Then
		    Return "Linux"
		  #ElseIf TargetMacOS Then
		    Return "Macintosh"
		  #Else
		    Return "Other"
		  #EndIf
		  
		End Function
	#tag EndMethod

	#tag Method, Flags = &h0
		Function ProjectName() As String
		  
		  Return "pvpgn-tracker"
		  
		End Function
	#tag EndMethod

	#tag Method, Flags = &h0
		Function TimeString(Seconds As UInt64) As String
		  
		  Dim buf As String
		  Dim weeks, days, hours, minutes As UInt64
		  
		  minutes = seconds \ 60
		  seconds = seconds Mod 60
		  
		  hours = minutes \ 60
		  minutes = minutes Mod 60
		  
		  days = hours \ 24
		  hours = hours Mod 24
		  
		  weeks = days \ 7
		  days = days Mod 7
		  
		  If seconds > 0 Then
		    buf = buf + " "
		    If seconds <> 1 Then buf = "s" + buf
		    buf = "second" + buf
		    buf = Format( seconds, "-#" ) + buf
		  End If
		  
		  If minutes > 0 Then
		    buf = buf + " "
		    If minutes <> 1 Then buf = "s" + buf
		    buf = "minute" + buf
		    buf = Format( minutes, "-#" ) + buf
		  End If
		  
		  If hours > 0 Then
		    buf = buf + " "
		    If hours <> 1 Then buf = "s" + buf
		    buf = "hour" + buf
		    buf = Format( hours, "-#" ) + buf
		  End If
		  
		  If days > 0 Then
		    buf = buf + " "
		    If days <> 1 Then buf = "s" + buf
		    buf = "day" + buf
		    buf = Format( days, "-#" ) + buf
		  End If
		  
		  If weeks > 0 Then
		    buf = buf + " "
		    If weeks <> 1 Then buf = "s" + buf
		    buf = "week" + buf
		    buf = Format( weeks, "-#" ) + buf
		  End If
		  
		  Return Left( buf, Len( buf ) - 1 )
		  
		End Function
	#tag EndMethod

	#tag Method, Flags = &h0
		Function ToHexString(value As String, delimiter As String = "") As String
		  
		  Dim buf As String
		  Dim i As Integer
		  Dim c As Integer
		  
		  c = LenB( value )
		  
		  For i = 1 To c
		    buf = buf + Right( "0" + Hex( AscB( MidB( value, i, 1 ))), 2 ) + delimiter
		  Next
		  
		  If Len( delimiter ) > 0 Then
		    buf = Left( buf, Len( buf ) - Len( delimiter ))
		  End If
		  
		  Return buf
		  
		End Function
	#tag EndMethod

	#tag Method, Flags = &h0
		Function VersionString() As String
		  
		  Dim buf As String
		  Dim buildNumber As Integer = Me.NonReleaseVersion
		  
		  #If DebugBuild Then
		    buildNumber = buildNumber + 1
		  #EndIf
		  
		  buf = buf + Format( Me.MajorVersion, "-#" ) + "."
		  buf = buf + Format( Me.MinorVersion, "-#" ) + "."
		  buf = buf + Format( Me.BugVersion, "-#" ) + "."
		  buf = buf + Format( buildNumber, "-#" )
		  
		  Return buf
		  
		End Function
	#tag EndMethod


	#tag Property, Flags = &h0
		ExitCode As Integer
	#tag EndProperty

	#tag Property, Flags = &h0
		ExitLoop As Boolean
	#tag EndProperty

	#tag Property, Flags = &h0
		TrackAPI As PvPGNTrackAPI
	#tag EndProperty

	#tag Property, Flags = &h0
		Tracker As PvPGNTracker
	#tag EndProperty


	#tag ViewBehavior
	#tag EndViewBehavior
End Class
#tag EndClass
